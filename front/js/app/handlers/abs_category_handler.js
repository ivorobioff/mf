/**
 * Абстрактный класс посредник для категорий и групп 
 */
var AbsCategoryHandler = Class.extend({
        
    cmenu: null,
    dom_element: null,
    
    handleCMenu: function(e){
        
        if (this.cmenu == null){
            this.cmenu = new ContextMenu({el: this.dom_element});
            this.cmenu.render();
        }
        
        this.cmenu.show({x: e.pageX, y: e.pageY});
    }
});