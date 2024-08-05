@if(isset($content['is_shop']) && $content['is_shop'] == 1)

    <svg class="h-12" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="88" viewBox="0 -960 960 960"
         width="48">
        <path
            d="M140.001-159.309v-465.844q-13.615-1.231-26.807-18.462-13.193-17.23-13.193-38.23v-100.46q0-23 17.082-40.346 17.082-17.346 40.611-17.346h644.612q23 0 40.346 17.346 17.347 17.346 17.347 40.346v100.46q0 21-13.193 38.23-13.192 17.231-26.807 18.462v465.844q0 23-17.347 41.154-17.346 18.154-40.346 18.154H197.694q-23.529 0-40.611-18.154-17.082-18.154-17.082-41.154Zm45.384-464.844v466.459q0 5.385 3.462 8.847 3.462 3.462 8.847 3.462h564.612q5.385 0 8.847-3.462 3.462-3.462 3.462-8.847v-466.459h-589.23Zm616.921-45.384q5.385 0 8.847-3.462 3.462-3.461 3.462-8.846v-100.46q0-5.385-3.462-8.847-3.462-3.462-8.847-3.462H157.694q-5.385 0-8.847 3.462-3.462 3.462-3.462 8.847v100.46q0 5.385 3.462 8.846 3.462 3.462 8.847 3.462h644.612ZM367.693-429.154h224.614v-45.384H367.693v45.384ZM185.385-145.385V-624.153v478.768Z"/>
    </svg>

@elseif (isset($content['content_type']) && $content['content_type'] == 'post')
    <svg class="h-12" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="88" viewBox="0 -960 960 960"
         width="48">
        <path
            d="M287.77-289.385h256.537v-45.384H287.77v45.384Zm0-167.923h384.46v-45.384H287.77v45.384Zm0-167.923h384.46v-45.384H287.77v45.384Zm-90.076 485.23q-23.529 0-40.611-17.082-17.082-17.082-17.082-40.611v-564.612q0-23.529 17.082-40.611 17.082-17.082 40.611-17.082h564.612q23.529 0 40.611 17.082 17.082 17.082 17.082 40.611v564.612q0 23.529-17.082 40.611-17.082 17.082-40.611 17.082H197.694Zm0-45.384h564.612q4.616 0 8.463-3.846 3.846-3.847 3.846-8.463v-564.612q0-4.616-3.846-8.463-3.847-3.846-8.463-3.846H197.694q-4.616 0-8.463 3.846-3.846 3.847-3.846 8.463v564.612q0 4.616 3.846 8.463 3.847 3.846 8.463 3.846Zm-12.309-589.23V-185.385-774.615Z"/>
    </svg>
@elseif (isset($content['subtype']) && $content['subtype'] == 'dynamic')

    <svg class="h-12" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="88" viewBox="0 96 960 960"
         width="48">
        <path
            d="M288.514 766.41h257.69v-50.255h-257.69v50.255Zm0-165.283h382.972v-50.254H288.514v50.254Zm0-165.282h382.972V385.59H288.514v50.255Zm-85.949 480.154q-25.788 0-44.176-18.388t-18.388-44.176v-554.87q0-25.788 18.388-44.176t44.176-18.388h554.87q25.788 0 44.176 18.388t18.388 44.176v554.87q0 25.788-18.388 44.176t-44.176 18.388h-554.87Zm0-50.255h554.87q4.616 0 8.462-3.847 3.847-3.846 3.847-8.462v-554.87q0-4.616-3.847-8.462-3.846-3.847-8.462-3.847h-554.87q-4.616 0-8.462 3.847-3.847 3.846-3.847 8.462v554.87q0 4.616 3.847 8.462 3.846 3.847 8.462 3.847Zm-12.309-579.488V865.744 286.256Z"/>
    </svg>

@elseif (isset($content['content_type']) && $content['content_type'] == 'product')

    <svg class="h-12" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="88" viewBox="0 -960 960 960"
         width="48">
        <path
            d="M140.001-159.309v-465.844q-13.615-1.231-26.807-18.462-13.193-17.23-13.193-38.23v-100.46q0-23 17.082-40.346 17.082-17.346 40.611-17.346h644.612q23 0 40.346 17.346 17.347 17.346 17.347 40.346v100.46q0 21-13.193 38.23-13.192 17.231-26.807 18.462v465.844q0 23-17.347 41.154-17.346 18.154-40.346 18.154H197.694q-23.529 0-40.611-18.154-17.082-18.154-17.082-41.154Zm45.384-464.844v466.459q0 5.385 3.462 8.847 3.462 3.462 8.847 3.462h564.612q5.385 0 8.847-3.462 3.462-3.462 3.462-8.847v-466.459h-589.23Zm616.921-45.384q5.385 0 8.847-3.462 3.462-3.461 3.462-8.846v-100.46q0-5.385-3.462-8.847-3.462-3.462-8.847-3.462H157.694q-5.385 0-8.847 3.462-3.462 3.462-3.462 8.847v100.46q0 5.385 3.462 8.846 3.462 3.462 8.847 3.462h644.612ZM367.693-429.154h224.614v-45.384H367.693v45.384ZM185.385-145.385V-624.153v478.768Z"/>
    </svg>

@else

    <svg class="h-12" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="88" viewBox="0 -960 960 960"
         width="48">
        <path
            d="M329.385-257.308h301.23v-45.384h-301.23v45.384Zm0-167.308h301.23v-45.383h-301.23v45.383Zm-91.691 324.615q-23.529 0-40.611-17.082-17.082-17.082-17.082-40.611v-644.612q0-23.529 17.082-40.611 17.082-17.082 40.611-17.082h347.537l194.768 194.768v507.537q0 23.529-17.082 40.611-17.082 17.082-40.611 17.082H237.694ZM562.539-644.77v-169.845H237.694q-4.616 0-8.463 3.846-3.846 3.847-3.846 8.463v644.612q0 4.616 3.846 8.463 3.847 3.846 8.463 3.846h484.612q4.616 0 8.463-3.846 3.846-3.847 3.846-8.463V-644.77H562.539ZM225.385-814.615v169.845-169.845 669.23V-814.615Z"/>
    </svg>

@endif


