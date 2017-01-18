SPODTUTORIAL = {};

SPODTUTORIAL.updateProgress = function ()
{
    $.ajax({
        type: 'post',
        url: SPODTUTORIAL.ajax_update_progress,
        success: function(){
            updateProgress();
            previewFloatBox.close();
        }
    });

};
