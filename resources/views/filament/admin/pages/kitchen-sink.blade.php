<x-filament-panels::page>

    {{$this->form}}


    <h1>
        Modal
        <code>
            mw.dialog({
                content: ''
            })
        </code>
    </h1>

    <hr>

    <h1>
        File picker
        <code>
        mw.filePickerDialog( (url) => {
            console.log(url)
        });
        </code>
    </h1>

    <hr>
    <h1>
        Url picker
        <code>
        var linkEditor = new mw.LinkEditor({
            mode: 'dialog',
        });
        var val = 'http://google.com'
        if(val) {
            linkEditor.setValue(val);
        }

        linkEditor.promise().then(function (data){
            var modal = linkEditor.dialog;
            if(data) {


            }
            modal.remove();
        });
        </code>
    </h1>

    <hr>
    <h1>
        Tree
    </h1>

    <hr>
    <h1>
        Editor
    </h1>

    <hr>

    <h1>
        Confirm dialog
    </h1>

    <hr>


    <h1>
        Iframe auto height
    </h1>

    <hr>


    <h1>
        Icon picker
    </h1>

    <hr>


</x-filament-panels::page>
