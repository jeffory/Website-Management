Vue.directive('inline-submit', {
    inserted: (el) => {
        const close_button = el.querySelector('button[name=close-status-dialog]');

        if (close_button) {
            close_button.addEventListener(
            'click', () => {
                el.classList.remove('had-error-submit');
            });
        }
    },
    bind: (el) => {
        el.addEventListener(
            'submit', (e) => {
                e.preventDefault();

                let form_instance = el;
                let validation_scope = NaN;

                if (el.hasAttribute('data-vv-scope')) {
                    validation_scope = el.getAttribute('data-vv-scope');
                }

                form_instance.classList.remove('had-successful-submit');
                form_instance.classList.remove('had-unsuccessful-submit');
                form_instance.classList.remove('had-error-submit');

                // Validation wrapper, ie. fail silently
                let validator = new Promise((resolve, reject) => {
                    if (window.app !== undefined && window.app.$validator !== undefined) {
                        window.app.$validator.validateAll(validation_scope).then(() => {
                            return resolve(el);
                        }).catch((error) => {
                            reject(error);
                        });
                    } else {
                        // If validation isn't available continue
                        resolve();
                    }
                });

                // Resets a form but ignores fields with readonly attributes
                function resetForm(form_element) {
                    let inputs = form_element.querySelectorAll('input, select, textarea');

                    if (! inputs) {
                        return false;
                    }

                    inputs.forEach(function (input) {
                        if (! input.hasAttribute('readonly')) {
                            input.value = '';
                        }
                    });
                }

                // Validation
                validator.then((el) => {
                    // Submission
                    const submit_button = el.querySelector('button[type="submit"],input[type="submit"]');
                    submit_button.disabled = true;

                    const form_data = new FormData(el);
                    const form_method = el.method.toLowerCase();
                    form_instance.classList.add('is-submitting');

                    Vue.http[form_method](el.action, form_data)
                        .catch((response) => {
                            submit_button.disabled = false;
                            form_instance.classList.remove('is-submitting');

                            // Wsas form submitted but had bad data...
                            if (response.status == 422) {
                                form_instance.classList.add('had-unsuccessful-submit');
                                form_instance.classList.add('has-message');
                            } else {
                                form_instance.classList.add('had-error-submit');
                                form_instance.classList.add('has-message');
                            }
                        })
                        .then((response) => {
                            // This function gets called even after an error
                            if (response === undefined) {
                                setTimeout(() => {
                                    form_instance.classList.remove('has-message');
                                }, 3000);
                                return;
                            }

                            submit_button.disabled = false;
                            form_instance.classList.remove('is-submitting');

                            if (response.data.hasOwnProperty('status') && response.data.status === false) {
                                // Form was received and processed but did not contain correct info
                                // Used for login forms etc.
                                form_instance.classList.add('had-unsuccessful-submit');
                                form_instance.classList.add('has-message');
                            } else {
                                form_instance.classList.add('had-successful-submit');
                                form_instance.classList.add('has-message');
                                // This clears readonly inputs too
                                resetForm(form_instance);

                                if (el.hasAttribute('data-redirect-on-success')) {
                                    setTimeout(() => {
                                        window.location.replace(el.getAttribute('data-redirect-on-success'));
                                    }, 2500);
                                }

                                if (el.hasAttribute('data-hide-modal-on-success')) {
                                    setTimeout(() => {
                                        window.eventbus.$emit('hide-modal', el.getAttribute('data-hide-modal-on-success'))
                                    }, 2500);
                                }
                            }

                            // Note the CSS may remove the visibility early
                            setTimeout(() => {
                                form_instance.classList.remove('has-message');
                            }, 3000);
                        });

                });
            });
    },
});
