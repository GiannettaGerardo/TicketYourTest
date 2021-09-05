<link rel="stylesheet" href="{{ URL::asset('/css/stile.css') }}">

<header class="rowP">
    <div id="headerLogo" class="rowP"><a href="{{route('home')}}"></a></div>
    <x-header.navbar />
    <div id="hamburgerMenu" class="columnP" onclick="openMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>
</header>
<script>
    function openMenu() {

        var hamburgerMenu = document.getElementById('hamburgerMenu');
        var mainNavBar = document.getElementById('mainNavBar');

        if (mainNavBar.classList.contains('hiddenDisplay')) {

            mainNavBar.classList.remove("hiddenDisplay");
            document.getElementById('headerLogo').style.zIndex = 0;

            let map = document.getElementById("map");
            if(map != null){

                map.classList.add("hiddenDisplay");
            }

        } else {

            mainNavBar.classList.add("hiddenDisplay");
            document.getElementById('headerLogo').style.zIndex = 1;

            let map = document.getElementById("map");
            if(map != null){

                map.classList.remove("hiddenDisplay");
            }
        }
    }
</script>