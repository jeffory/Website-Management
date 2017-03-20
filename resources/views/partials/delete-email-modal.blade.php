<modal ref="delete_email" v-cloak :modal_data="modal_data">
    <section>
        <h3>
            Are you sure you wish to delete the email?<br>
            <strong class="has-text-centered">@{{ modal_data.email }}</strong>
        </h3>
    </section>

    <form method="post" action="{{ route('server.deleteEmail') }}" id="delete_email_form">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}

        <input type="hidden" name="email" :value="modal_data.email">
        
        <div class="control is-grouped">
            <p class="control">
                <button type="submit" class="button is-danger is-large">
                    <span class="icon is-small">
                        <i class="fa fa-check"></i>
                    </span>

                    <span>Yes</span>
                </button>
            </p>

            <p class="control">
                <button type="button" class="button is-large" @click="hideModal('delete_email')">
                    <span class="icon is-small">
                        <i class="fa fa-times"></i>
                    </span>

                    <span>No</span>
                </button>
            </p>
        </div>
    </form>
</modal>