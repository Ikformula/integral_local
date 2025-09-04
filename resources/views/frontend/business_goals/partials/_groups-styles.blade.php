<style>
    .modal {
        top: 2rem;
    }

    .modal-fullscreen {
        padding: 0 !important;
    }
    .modal-fullscreen .modal-dialog {
        width: 98%;
        max-width: none;
        height: 95%;
        margin: 1rem;
    }
    .modal-fullscreen .modal-content {
        height: 95%;
        border: 0;
        border-radius: 5px;
    }
    .modal-fullscreen .modal-body {
        overflow-y: auto;
    }

    .modal-body {
        max-height: calc(100vh - 143px);
        overflow-y: auto;
    }


    .scrollable-div {
        height: 70vh; /* Change this value to set the desired fixed height */
        overflow: auto; /* This will enable vertical scrolling if content exceeds the height */
        padding-top: 0;
    }

    thead {
        position: sticky;
        top: 0;
        background-color: #fff;
        z-index: 9;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        -webkit-box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        -moz-box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    }

</style>
