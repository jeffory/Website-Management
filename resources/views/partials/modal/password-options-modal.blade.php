<modal id="email-options-modal" v-cloak>

    <template v-if="eventbus.modal.hasOwnProperty('email-options-modal')">
        <tabs>
            <tab label="Password Change">
                <section>
                    <h3>
                        Password Change
                    </h3>
                </section>
        
                <form method="post" autocomplete="off" v-inline-submit
                      action="{{ route('server_email.update', ['remoteserver' => $domain]) }}"
                      :action="'{{ route('server_email.update', ['remoteserver' => $domain]) }}' + '/' + eventbus.modal['email-options-modal'].email"
                      :reset="true" data-vv-scope="password_change_form" id="password_change_form"
                      data-hide-modal-on-success="email-options-modal">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
        
                    @include('partials/form-status-indicator')
                    
                    <div class="control">
                        <label class="control-label">Email</label>
                    
                        <p class="control">
                            <input name="email" class="input readonly" type="text"
                                   :value="eventbus.modal['email-options-modal'].email" readonly>
                        </p>
                    </div>
                    
                    <div class="columns">
                        <div class="column">
                            <div class="control">
                                <label class="control-label">Password</label>
                
                                <input name="password" class="input" type="password"
                                       v-validate="'required|cpanel_verify'" data-vv-delay="500"
                                       :class="{ 'is-danger': errors.has('password_change_form.password') }"
                                       v-model="password_change_form.password">
                
                                <span v-show="errors.has('password_change_form.password')" class="help is-danger" v-cloak>
                                    Score: @{{ password_strength }} / 50 -
                                    @{{ errors.first('password_change_form.password') }}
                                </span>
                            </div>
                
                            <div class="control">
                                <label class="control-label">Confirm password</label>
                
                                <p class="control">
                                    <input name="password_confirmation" type="password" class="input"
                                           :class="{ 'is-danger': errors.has('password_change_form.password_confirmation') }"
                                           v-validate="'confirmed:password_change_form.password'">
                
                                    <span v-show="errors.has('password_change_form.password_confirmation')"
                                          class="help is-danger" v-cloak>
                                        The password confirmation does not match the original password.
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="column is-5">
                            <div class="message is-info">
                                <p class="message-body">
                                    The server requires a password with mixed-case letters and symbols, avoiding common words where possible.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control is-grouped">
                        <p class="control">
                            <button type="submit" class="button is-primary">Change password</button>
                        </p>
                    
                        <p class="control">
                            <button type="button" class="button" @click="eventbus.$emit('hide-modal', 'email-options-modal')">Cancel</button>
                        </p>
                    </div>
                </form>
            </tab>
        
            <tab label="Password Verification">
                <section>
                    <h3>
                        Password Verification
                    </h3>
                </section>
        
                <form method="post"
                      action="{{ route('server_email.account_check', ['remoteserver' => $domain]) }}"
                      :action="'{{ route('server_email.account_check', ['remoteserver' => $domain]) }}' + '/' + eventbus.modal['email-options-modal'].email"
                      :reset="true"
                      data-vv-scope="password_verify_form" autocomplete="off" v-inline-submit>
                    {{ csrf_field() }}

                    <div class="status-indicator">
                        <div class="status-message status-submitting">
                            <p>
                                <span class="icon is-extra-large">
                                    <i class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
                                </span>
                            </p>
                        </div>

                        <div class="status-message status-successful">
                            <p>
                                <span class="icon is-extra-large fa-stack">
                                    <i class="fa fa-circle fa-stack-2x" aria-hidden="true"></i>
                                    <i class="fa fa-check fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                </span>

                                <p>
                                    Success, account verified.
                                </p>
                            </p>
                        </div>

                        <div class="status-message status-unsuccessful">
                            <p>
                                <span class="icon is-extra-large fa-stack">
                                    <i class="fa fa-circle fa-stack-2x" aria-hidden="true"></i>
                                    <i class="fa fa-exclamation-triangle fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                </span>
                            </p>

                            <p>
                                Account password appears to be incorrect.
                            </p>
                        </div>

                        <div class="status-message status-error has-text-centered">
                            <p>
                                <span class="icon is-extra-large fa-stack">
                                    <i class="fa fa-circle fa-stack-2x" aria-hidden="true"></i>
                                    <i class="fa fa-times fa-stack-1x fa-inverse" aria-hidden="true"></i>
                                </span>
                            </p>
                            
                            <p>
                                There was an error submitting the request to the server.<br>
                                Please check your internet connection and try again.
                            </p>

                            <p>
                                <button class="button" type="button" name="close-status-dialog">Okay</button>
                            </p>
                        </div>
                    </div>
                    
                    <div class="control">
                        <label class="control-label">Email</label>
                    
                        <p class="control">
                            <input name="email" class="input readonly" type="text" :value="eventbus.modal['email-options-modal'].email" readonly>
                        </p>
                    </div>
                    
                    <div class="control">
                        <label class="control-label">Password</label>
        
                        <p class="control">
                            <input name="password" class="input" type="password" required>
                        </p>
                    </div>
                    
                    <span v-show="errors.has('password_verify_form.password')" class="help is-danger" v-cloak>
                        @{{ errors.first('password_verify_form.password') }}
                    </span>
                    
                    <div class="control is-grouped">
                        <p class="control">
                            <button type="submit" class="button is-primary">Check account</button>
                        </p>
        
                        <p class="control">
                            <button type="button" class="button" @click="eventbus.$emit('hide-modal', 'email-options-modal')">Cancel</button>
                        </p>
                    </div>
                </form>
        
            </tab>
        </tabs>
    </template>

</modal>