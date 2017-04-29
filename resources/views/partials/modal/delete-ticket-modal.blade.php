<modal id="delete-ticket-modal" v-cloak align="center">
    <section>
        <h3>
            Are you sure you wish to delete the ticket?<br>
        </h3>
        <strong class="big" style="font-size: 1.2em">{{ $ticket["title"] }}</strong>
    </section>
    <br>

    <form method="post" action="{{ route('tickets.destroy', $ticket['id']) }}" id="delete_email_form">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        
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
                <button type="button" class="button is-large" @click="eventbus.$emit('hide-modal', 'delete-ticket-modal')">
                    <span class="icon is-small">
                        <i class="fa fa-times"></i>
                    </span>

                    <span>No</span>
                </button>
            </p>
        </div>
    </form>
</modal>