<style>
    /* .st-Wrapper{
        width: 40% !important;
    }
    .st-Email{
        width: 45% !important;
    } */
    .st-Spacer.st-Spacer--kill.st-Spacer--height{
        display: none !important
    }
    div{
        /* display: none !important */
    }
    /* table.st-Preheader ~ div:not(table.st-Copy) ~ table.st-Copy {
        display: none;
    } */
    table.st-Preheader ~ div {
        /* display: none !important; */
        height: 10px !important;
        overflow: hidden;
    }
    table.st-Copy {
        margin-top: 20px;
        min-width: 100% !important;
        width: 100% !important;
    }
    table.st-Copy ~ table.st-Copy {
        margin-top: 0px;
    }
    table.st-Copy.st-Copy--caption.st-Width.st-Width--mobile > tbody > tr > td{
        width: 100% !important
    }
    .st-Spacer.st-Spacer--standalone.st-Width.st-Width--mobile ~ table.st-Copy.st-Copy--caption.st-Width.st-Width--mobile > tbody > tr > td{
        width: 8px !important
    }
</style>
{!! $invoiceContents !!}