<modal id="delete-email-modal" v-cloak align="center">
    <template v-if="eventbus.modal.hasOwnProperty('delete-email-modal')">
        <section>
            <h3>
                Are you sure you wish to delete the email?<br>
            </h3>

            <p class="has-text-centered" style="color: #000; font-size: 1.3em; font-weight: bold">@{{ eventbus.modal['delete-email-modal'].email }}</p>
        </section>

        <form method="post"
              :action="'{{ route('server_email.destroy', ['remoteserver' => $domain]) }}' + '/' + eventbus.modal['delete-email-modal'].email"
              id="delete_email_form">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}

            <input type="hidden" name="email" :value="eventbus.modal['delete-email-modal'].email">
            
            <div class="control is-grouped has-text-centered">
                <p class="control">
                    <button type="submit" class="button is-danger is-large">
                        <span class="icon is-small">
                            <i class="fa fa-check"></i>
                        </span>

                        <span>Yes</span>
                    </button>
                </p>

                <p class="control">
                    <button type="button" class="button is-large" @click="eventbus.$emit('hide-modal', 'delete-email-modal')">
                        <span class="icon is-small">
                            <i class="fa fa-times"></i>
                        </span>

                        <span>No</span>
                    </button>
                </p>
            </div>
        </form>
    </template>
</modal>