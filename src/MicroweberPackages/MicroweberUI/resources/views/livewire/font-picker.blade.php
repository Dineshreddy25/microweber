<div>

    <div class="form-control-live-edit-label-wrapper"
         x-data="{selectedOption: {{json_encode([])}}, openOptions:false,styles: {}}"

         x-init="
         $watch('selectedOption', val => styles['font-family'] = selectedOption.family)
        "
    >

        <button type="button" class="form-select form-control-live-edit-input"
                x-on:click="openOptions = !openOptions">
            <span x-html="selectedOption.family" :style="styles"></span>
        </button>


        <div class="dropdown-menu form-control-live-edit-input ps-0" :class="[openOptions ? 'show':'']">

            <div>
                <div>
                    <x-microweber-ui::input wire:model="search" type="text" placeholder="Search fonts..." />
                </div>
            </div>

            <div class="d-flex m-3 gap-3">
                <button class="btn btn-light btn-sm btn-outline">
                    All
                </button>
                <button class="btn btn-light btn-sm btn-outline">
                    Favorites
                </button>
            </div>

            @if(!empty($fonts))
                @foreach($fonts as $font)

                    <button type="button" x-on:click="selectedOption = {{json_encode($font)}}; openOptions = false" :class="[selectedOption.key == '{{$font['family']}}' ? 'active':'']" class="dropdown-item tblr-body-color">

                        <span style="font-size:18px;font-family:'{!! $font['family'] !!}',sans-serif;">
                             {!! $font['family'] !!}
                        </span>

                        <span class="ms-auto" x-show="selectedOption.key == '{{$font['family']}}'">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 -960 960 960" width="16"><path d="M378-246 154-470l43-43 181 181 384-384 43 43-427 427Z"/></svg>
                    </span>
                    </button>

                @endforeach
            @endif
            <div>
                <div class="mt-3">
                    {!! $fonts->links('livewire-tables::specific.bootstrap-4.pagination') !!}
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function () {
            @foreach($fonts as $font)
            loadFontFamily('{{$font['family']}}');
            @endforeach
        }

        window.addEventListener('font-picker-load-fonts', function (e) {
            if (e.detail.fonts) {
                for (var i in e.detail.fonts) {
                    loadFontFamily(e.detail.fonts[i]['family']);
                }
            }
        });

        function loadFontFamily(family) {

            if (!family) {
                return;
            }

            var filename = "//fonts.googleapis.com/css?family=" + encodeURIComponent(family) + "&text=" + encodeURIComponent(family);
            var fileref = document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
            fileref.setAttribute("crossorigin", "anonymous")
            fileref.setAttribute("data-noprefix", "1")


            var fileref2 = document.createElement("link")
            fileref2.setAttribute("rel", "stylesheet")
            fileref2.setAttribute("type", "text/css")
            fileref2.setAttribute("href", filename);
            fileref2.setAttribute("crossorigin", "anonymous")
            fileref2.setAttribute("data-noprefix", "1")

            if(self !== top){
                mw.top().doc.getElementsByTagName("head")[0].appendChild(fileref)
                document.getElementsByTagName("head")[0].appendChild(fileref2)
            } else {
                document.getElementsByTagName("head")[0].appendChild(fileref)
            }
        }
    </script>
</div>
