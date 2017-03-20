<modal ref="change_email_password" v-cloak :modal_data="modal_data">
    <tabs>
        <tab label="Password Change">
            <section>
                <h3>
                    Password Change
                </h3>
            </section>

            <form method="post" autocomplete="off" v-inline-submit action="{{ route('server.emailChangePassword') }}" :reset="true" data-vv-scope="password_change_form" id="password_change_form">
                {{ csrf_field() }}

                @include('partials/form-status-indicator')
                
                <div class="control">
                    <label class="control-label">Email</label>
                
                    <p class="control">
                        <input class="input" type="text" :value="modal_data.email" disabled>
                    </p>
                </div>
                
                <div class="control">
                    <label class="control-label">Password</label>

                    <input name="password" class="input" type="password" v-validate="'required|cpanel_verify'" data-vv-delay="500" :class="{ 'is-danger': errors.has('password_change_form.password') }" v-model="password_change_form.password">

                    <span v-show="errors.has('password_change_form.password')" class="help is-danger" v-cloak>
                        Score: @{{ password_strength }} / 50 -
                        @{{ errors.first('password_change_form.password') }}
                    </span>
                </div>

                <div class="control">
                    <label class="control-label">Confirm password</label>

                    <p class="control">
                        <input name="password_confirmation" type="password" class="input" :class="{ 'is-danger': errors.has('password_change_form.password_confirmation') }" v-validate="'confirmed:password_change_form.password'">

                        <span v-show="errors.has('password_change_form.password_confirmation')" class="help is-danger" v-cloak>
                            The password confirmation does not match the original password.
                        </span>
                    </p>
                </div>
                
                <div class="control is-grouped">
                    <p class="control">
                        <button type="submit" class="button is-primary">Change password</button>
                    </p>
                
                    <p class="control">
                        <button type="button" class="button" @click="hideModal('change_email_password')">Cancel</button>
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

            <form method="post" autocomplete="off" v-inline-submit action="" :reset="true" data-vv-scope="password_verify_form">
                {{ csrf_field() }}

                @include('partials/form-status-indicator')
                
                <div class="control">
                    <label class="control-label">Email</label>
                
                    <p class="control">
                        <input class="input" type="text" :value="modal_data.email" disabled>
                    </p>
                </div>
                
                <div class="control">
                    <label class="control-label">Password</label>

                    <p class="control">
                        <input name="password" class="input" type="password" v-model="password_verify_form.password">
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
                        <button type="button" class="button" @click="hideModal('change_email_password')">Cancel</button>
                    </p>
                </div>
            </form>

        </tab>
    </tabs>
</modal>