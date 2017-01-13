SPODTUTORIAL = {};

SPODTUTORIAL.showAbout = function ()
{
    $.ajax({
        type: 'post',
        url: SPODTUTORIAL.ajax_show_about,
        success: function(stringa){
            showStr(stringa);
        }
    });

};
