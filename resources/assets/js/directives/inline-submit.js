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

                console.log('validation_scope: ', validation_scope);

                // Validation wrapper, ie. fail silently
                let validator = new Promise((resolve, reject) => {
                    if (window.app !== undefined && window.app.$validator !== undefined) {
                        window.app.$validator.validateAll(validation_scope).then(() => {
                            console.log('validator_promise::then');
                            return resolve(el);
                        }).catch((error) => {
                            console.log('validator_promise::catch');
                            console.log(window.app.$validator.errorBag.all());
                            reject(error);
                        });
                    } else {
                        // If validation isn't available continue
                        resolve();
                    }
                });

                // Validation
                validator.then((el) => {
                    console.log('validator::then');

                    // Submission
                    let submit_button = el.querySelector('button[type="submit"]');
                    submit_button.disabled = true;

                    const form_data = new FormData(el);
                    const form_method = el.method.toLowerCase();
                    form_instance.classList.add('is-submitting');

                    Vue.http[form_method](el.action, form_data)
                        .catch(() => {
                            submit_button.disabled = false;
                            form_instance.classList.remove('is-submitting');
                            form_instance.classList.add('had-error-submit');
                        })
                        .then((response) => {
                            // This function gets called even after an error
                            if (response === undefined) {
                                return;
                            }

                            submit_button.disabled = false;
                            form_instance.classList.remove('is-submitting');

                            if (true) {
                                form_instance.classList.add('had-successful-submit');
                                form_instance.reset();

                                if (el.hasAttribute('data-redirect-on-success')) {
                                    setTimeout(() => {
                                        window.location.replace(el.getAttribute('data-redirect-on-success'));
                                    }, 500);
                                }
                            } else {
                                form_instance.classList.add('had-unsuccessful-submit');
                            }

                            // Note the CSS may remove the visibility early
                            setTimeout(() => {
                                form_instance.classList.remove('had-successful-submit');
                                form_instance.classList.remove('had-unsuccessful-submit');
                            }, 4000);
                        });

                });

                // .catch(() => {
                //     console.log('validator::catch');
                //     form_instance.classList.add('had-unsuccessful-submit');

                //     setTimeout(() => {
                //         form_instance.classList.remove('had-unsuccessful-submit');
                //     }, 3000);
                // })
            });
    },
});
