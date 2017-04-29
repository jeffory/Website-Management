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
                Form submission sucessful.
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
            Error processing the form.<br>
            Please check the fields and try again.
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