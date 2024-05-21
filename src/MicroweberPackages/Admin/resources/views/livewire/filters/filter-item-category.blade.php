<div>

    <button type="button"  class="btn btn-badge-dropdown btn-outline-dark js-dropdown-toggle-{{$this->getId()}} @if($itemCategoryValue || $itemPageValue) btn-secondary @else btn-outline-secondary @endif btn-sm icon-left">

        @if($itemCategoryValue || $itemPageValue)
            {{$name}}:

            @if ($firstItemCategoryName && $firstItemPageName)
            {{$firstItemCategoryName}}, {{$firstItemPageName}}...
            @elseif ($firstItemCategoryName)
            {{$firstItemCategoryName}}...
            @elseif ($firstItemPageName)
                {{$firstItemPageName}}...
            @endif

            <span class="badge badge-filter-item mt-1">
                + {{$selectedItemsCount}}
            </span>

        @else
            {{$name}}
        @endif



        <div class="d-flex actions">
            <div class="action-dropdown-icon"><svg fill="currentColor"  xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M480-344 240-584l43-43 197 197 197-197 43 43-240 240Z"/></svg></div>
         {{--  @if($selectedItem)
                <div class="action-dropdown-delete" wire:click="resetProperties"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
            @endif--}}
            <div class="action-dropdown-delete" wire:click="hideFilterItem('{{$this->getId()}}')"><svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="m291-240-51-51 189-189-189-189 51-51 189 189 189-189 51 51-189 189 189 189-51 51-189-189-189 189Z"/></svg></div>
        </div>


    </button>

    <div class="badge-dropdown position-absolute js-dropdown-content-{{$this->getId()}} @if($showDropdown) active @endif " style="width:400px;">

        <input wire:model.live.stop="itemCategoryValue" id="js-filter-category" type="hidden" />
        <input wire:model.live.stop="itemPageValue" id="js-filter-page" type="hidden" />

        <div id="js-filter-item-tree-{{$this->getId()}}" wire:ignore></div>

    </div>

<div wire:ignore>

    <script>
        $(document).ready(function() {
            $('body').on('click', function(e) {
                if (!mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target,['js-dropdown-toggle-{{$this->getId()}}','js-dropdown-content-{{$this->getId()}}'])) {
                    $('.js-dropdown-content-{{$this->getId()}}').removeClass('active');
                }
            });
            $('.js-dropdown-toggle-{{$this->getId()}}').click(function () {
                $('.js-dropdown-content-{{$this->getId()}}').toggleClass('active');
            });
        });
    </script>

    <script id="js-category-filter-select-tree-<?php echo time(); ?>">

        var pageElement = document.getElementById('js-filter-page');
        var categoryElement = document.getElementById('js-filter-category');

        var selectedPages = pageElement.value.split(",");
        var selectedCategories = categoryElement.value.split(",");

        var tree;

        mw.admin.tree(document.getElementById('js-filter-item-tree-{{$this->getId()}}'), {
            options: {
                sortable: false,
                singleSelect: false,
                selectable: true,
                multiPageSelect: true
            }
        }, 'treeTags').then(function (res) {

            tree = res.tree;

            $(tree).on("ready", function () {
                if (selectedPages.length) {
                    $.each(selectedPages, function (key, pageId) {
                        tree.select(pageId, 'page', false)
                    });
                }

                if (selectedCategories.length > 0) {
                    $.each(selectedCategories, function (key, catId) {
                        tree.select(catId, 'category', false);
                    });
                }
            });

            $(tree).on("selectionChange", function () {

                selectedPages = [];
                selectedCategories = [];

                $.each(tree.getSelected(), function (key, item) {
                    if (item.type == 'category') {
                        selectedCategories.push(item.id);
                    }
                    if (item.type == 'page') {
                        selectedPages.push(item.id);
                    }
                });

                pageElement.value = selectedPages.join(",");
                pageElement.dispatchEvent(new Event('input'));

                categoryElement.value = selectedCategories.join(",");
                categoryElement.dispatchEvent(new Event('input'));

                window.Livewire.dispatch('setFirstPageContentList');
            });

        });
    </script>
</div>
</div>
