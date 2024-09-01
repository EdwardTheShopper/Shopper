/* handle opening and closing of sub-menus in categories menu */
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.canvas-main .toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            console.log('yeyyeyeye');
            const submenu = this.nextElementSibling; // get the following submenu
            if(submenu) { // exists
                if(submenu.style.display === 'none' || submenu.style.display === '') { // submenu is closed
                    
                    // TODO: add animation
                    submenu.style.display = 'block'; // open submenu

                    // flip arrow
                    this.classList.remove('fa-angle-down');
                    this.classList.add('fa-angle-up');
                }
                else collapse_all_nested_menus(submenu, this);
            }
        });
    });
});

function collapse_all_nested_menus(parent_menu, arrow_icon) {

    // TODO: add animation

    parent_menu.style.display = 'none';
    
    // flip arrow
    arrow_icon.classList.remove('fa-angle-up');
    arrow_icon.classList.add('fa-angle-down');

    // TODO: call recursively
    /* pseudo code:
        foreach child of parent_menu {
            if(child not contains class="without-children") {
                get the arrow element
                collapse_all_nested_menus(child, child_arrow)
            } 
        
        }
    */
}