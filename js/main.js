$(document).ready(() => {
    // == Main ==
    groupInChargeFilter();

    // == Functions ==
    function groupInChargeFilter(){
        const groups = {
            red: ['group_1'],
            blueviolet: ['group_2'],
            darkgreen: ['group_3'],
            darkorange: ['group_4']
        };
        $('.td-group span').each(function() {
            var el = $(this);
            el.css('background-color', `${getKeyOfValue(groups, el.text())}`);
        });
    }

    // == utility functions ==
    function getKeyOfValue(obj, value) {
        return Object.keys(obj).filter(key => obj[key].includes(value))[0];
    }
})
