<template>
    <div class="file-uploader">
        <div class="columns">
            <div class="column is-2">
                <label class="button is-primary is-outlined">
                    <span class="icon is-small">
                        <i class="fa fa-paperclip"></i>
                    </span>
            
                    <span>Attach file(s)</span>
            
                    <input type="file" id="file-input" ref="file-uploader" multiple @change.prevent="upload">
                </label>
            </div>
            
            <div class="column is-10">
                <p class="file-list-placeholder" v-if="attachedFiles.length == 0">
                    (Accepted file types: txt, doc, docx, xls, pdf, jpeg, png, jpg)
                </p>

                <ul class="file-list" v-else>
                    <li class="file-list-item tag is-medium" v-for="file in attachedFiles">
                        <progress :value="file.percentage" max="100" class="progress"
                            v-bind:class="{'is-primary': file.percentage < 100, 'is-success':  file.percentage == 100, 'is-danger': file.hasError}">
                        </progress>

                        <span>{{ file.name }}</span>

                        <button class="delete is-small" @click.prevent="removeUpload(file.index)" v-if="file.token"></button>
                        <input type="hidden" name="ticket_file[]" v-if="file.token" :value="file.token">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'uploadTo'
        ],
        data() {
            return {
                attachedFiles: [],
                uploadsInProgress: 0
            }
        },
        mounted() {
            eventbus.$on('clearAttachments', this.clearAttachments);
        },
        methods: {
            upload(e) {
                let self = this;

                let file_upload_input = self.$refs['file-uploader'];

                _.each(file_upload_input.files, (file) => {
                    let index = self.attachedFiles.length;
                    self.uploadsInProgress++;

                    self.attachedFiles.push({
                        index: index,
                        name: file.name,
                        percentage: 0,
                        hasError: false,
                        token: ''
                    });

                    self.$emit('upload-started', self.attachedFiles[index]);

                    new ChunkUploader(file, "upload_file", self.uploadTo,
                    {
                        progress: (percentageDone) => {
                            self.updateUploadStatus(index, { percentage: percentageDone });
                        },
                        error: () => {
                            self.updateUploadStatus(index, { percentage: 100, hasError: true });
                            self.uploadsInProgress--;
                            
                            if (self.uploadsInProgress == 0) {
                                file_upload_input.value = "";
                            }
                        },
                        complete: (response) => {
                            self.updateUploadStatus(index, {
                                percentage: 100,
                                token: response.data.token
                            });

                            self.uploadsInProgress--;
                            self.$emit('upload-completed', _.find(this.attachedFiles, {'index': index}));

                            if (self.uploadsInProgress == 0) {
                                file_upload_input.value = "";
                            }
                        }
                    });

                });
            },
            removeUpload(index) {
                var file = _.find(this.attachedFiles, {'index': index});

                Vue.http.delete('/client-area/tickets/delete_file_upload/' + file.token);
                self.$emit('upload-removed', file);

                this.attachedFiles = _.reject(this.attachedFiles, (file) => {
                    return file.index == index;
                });
            },
            updateUploadStatus(index, data) {
                var file = _.find(this.attachedFiles, {'index': index});
                
                _.each(data, (value, key) => {
                    if (key in file) {
                        file[key] = value;
                    }
                });
            },
            clearAttachments() {
                this.attachedFiles = [];
                this.uploadsInProgress = 0;
            }
        }
    }

    // ---- ChunkUploader ----

    class ChunkUploader {
        constructor(file, formFieldName, postURL, options) {

            if (! 'axios' in window) {
                console.error('Error: ChunkUploader class requires axios library to function!')
                return;
            }

            this.file = file;
            this.formFieldName = formFieldName;
            this.postURL = postURL;

            this.fileSize = file.size;
            this.chunkSize = (1024 * 1024);
            this.chunkRangeStart = 0;
            this.chunkRangeEnd = this.chunkSize;

            this.retries = 3;

            this.options = options;

            this.uploadChunk();
        }

        uploadChunk() {
            let self = this;

            if (self.chunkRangeEnd > self.fileSize) {
                self.chunkRangeEnd = self.fileSize;
            }

            let fileChunk = self.file['slice'](self.chunkRangeStart, self.chunkRangeEnd)
            let formData = new FormData();

            formData.append(self.formFieldName, fileChunk, self.file.name);

            axios.post(self.postURL, formData, {
                headers: {
                    'Content-Range': `bytes ${this.chunkRangeStart}-${this.chunkRangeEnd}/${this.fileSize}`
                },
                onUploadProgress(e) {
                    if ('progress' in self.options) {
                        if (e.lengthComputable) {
                            self.options['progress'].call(self,
                                ((self.chunkRangeStart + e.loaded) / self.fileSize) * 100
                            );
                        }
                    }
                }
            })
            .catch(function (error) {
                self.chunkError(error);
            })
            .then(function (response) {
                self.chunkComplete(response);
            });
        }

        chunkError(error) {
            let self = this;

            // Try the chunk again on failure
            if (self.retries > 0) {
                self.retries--;

                setTimeout(() => {
                    return self.uploadChunk();
                }, 3000);
            }

            self.options['error'].call(self, error);
        }

        chunkComplete(response) {
            let self = this;

            if (self.chunkRangeEnd == self.fileSize) {
                self.options['complete'].call(self, response);
            } else {
                self.chunkRangeStart = self.chunkRangeEnd;
                self.chunkRangeEnd = self.chunkRangeStart + self.chunkSize;
                self.uploadChunk();
            }
        }
    }
</script>

<style type="text/css">
    .file-uploader {
        margin-top: .5em;
        padding: .2em;
    }

    .file-uploader input[type="file"] {
        display: none;
    }

    .file-list {
        display: inline-block;
        margin: 0;
        padding: .15em 1em
    }

    .file-list-placeholder {
        padding: .45em 0;
    }

    .file-list-item {
        position: relative;
        margin-bottom: .5em;
        margin-right: .5em;
    }

    .file-list-item progress {
        height: 100%;
        position: absolute;
        width: 100%;
        z-index: 0;
    }

    .file-list-item span,
    .file-list-item button {
        z-index: 1;
    }
</style>