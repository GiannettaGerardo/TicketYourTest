<header class="rowP">
    <div id="headerLogo" class="rowP"><a href=""></a></div>
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
            document.getElementById('headerForm').style.zIndex = 0;
        } else {
            mainNavBar.classList.add("hiddenDisplay");
            document.getElementById('headerForm').style.zIndex = 1;
        }
    }
</script>