<div>

    @script


    <script>

        mw.lib.require('jqueryui')
        mw.lib.require('nestedSortable')
        document.addEventListener('livewire:initialized', async () => {




        })
    </script>

    @endscript
    <div>

        <style>
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
            ul ul {
                margin-left: 20px;
            }
        </style>

        <script>
            function sortableData() {
                return {
                    async init() {


                        const collect = target => {
                            if(!target) {
                                console.log('target is not defined')
                                return [];
                            }
                            return Array.from(target.querySelectorAll('li')).map(node => {
                                const parent = node.parentNode.closest('li');
                                return {
                                    id: node.dataset.itemId,
                                    parentId: parent  ? parent.dataset.itemId : 0
                                }
                            });
                        }

                        var _orderChangeHandleTimeout = null;

                        var _orderChangeHandle = function (e, ui){
                            clearTimeout(_orderChangeHandleTimeout);
                            _orderChangeHandleTimeout = setTimeout(function(){
                               var result = collect(e.target);

                               console.log(result)

                            }, 100);
                        };




                        var sortableLists = $('ul','.admin-menu-items-holder');

                        for (var i = 0; i < sortableLists.length; i++) {

                            $(sortableLists[i]).nestedSortable({
                                items: ".menu_element",
                                listType:'ul',
                                //handle:'.menu_element',
                                start: function(){ // firefox triggers click when drag ends
                                    // scope._disableClick = true;
                                },
                                stop: function(){
                                    //  setTimeout(() => {scope._disableClick = false;}, 78)
                                },
                                update:function(e, ui){
                                    _orderChangeHandle(e, ui);
                                }
                            });


                        }



                        //
                        // //onclick on .menu_element alert
                        // var menuElements = document.querySelectorAll('.admin-menu-items-holder .menu_element_link');
                        // for (var i = 0; i < menuElements.length; i++) {
                        //     if (menuElements[i].classList.contains('binded-click')) {
                        //         continue;
                        //     }
                        //     menuElements[i].classList.add('binded-click');
                        //
                        //
                        //     menuElements[i].addEventListener('click', (e) => {
                        //         e.stopPropagation();
                        //         e.preventDefault();
                        //
                        //         var id = e.target.getAttribute('data-item-id');
                        //         //  Livewire.dispatch('editMenuItem',{id: id});
                        //
                        //         this.$wire.mountAction('editAction', {id: id})
                        //
                        //
                        //         //s  alert(id)
                        //     });
                        //
                        // }

                    },
                    updateOrder(event) {

                    }
                }
            }


        </script>
    </div>

    <div x-data="sortableData()" x-init="init()">


        <div class="admin-menu-items-holder">


            @foreach ($menus as $menu)

                <div>


                    @php
                        $params = array(
                          'menu_id' => $menu->id,

                      );
                         print  menu_tree($params);
                    @endphp


                    <h2> {{ $menu->id }}  {{ $menu->title }}   {{ $menu->item_type }}</h2>


                    {{ ($this->editAction)(['id' => $menu->id]) }}


                    {{ ($this->deleteAction)(['id' => $menu->id]) }}

                </div>
            @endforeach

        </div>


        aaaaaa


    </div>


    <x-filament-actions::modals/>
</div>
