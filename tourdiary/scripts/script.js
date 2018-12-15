$(function(){
    // console.log("Почали");

    $("body").on("click","#loginButton",function(){
        // console.log("click");
        $("#loginWrapper").toggle();
    });

    $("body").on("click","#addNoteButton",function(){
        // console.log("click");
        $("#addNoteWrapper").toggle();
    });

    $("body").on("click","#addPhoto",function(){
        // console.log("click");
        $("ul#addPhotoList").append('<li><input type="file" name="image[]"><span class="del-photo">x</span></li>');
    });

    $("body").on("click",".del-photo",function(){
        console.log("kuku");

        $(this).parent().remove();
    });

    $("body").on("click","#latlng",function(){
        // console.log("click");
        
        $("#addNoteWrapper").hide();
        $("#mapid").addClass("crossheir");

        mymap.on('click', fillLatLng);


    });


    $("body").on("click",".screen-wrapper",function(e){
        if (e.target === this) {
            // console.log(this);
            if (e.target.id === "noteWrapper") {
                // console.log("boom");
                $(this).remove();
            }
            else {
                $(this).hide();
            }
        }
    });

    $("body").on("click",".close-wrapper",function(e){

        var wrapperId = $(this).parent().parent().attr('id');

        // console.log(wrapperId);

        if (wrapperId == "noteWrapper") {
            // console.log("boom");
            $("#"+wrapperId).remove();
        }
        else {
            $("#"+wrapperId).hide();
        }
    });
    
    $("body").on("click",".detailsButton",function(e){
        // console.log($(this).parent().children(".note").html());

        $("body").append('<div class="screen-wrapper" id="noteWrapper"><div><div class="close-wrapper">x</div>'+
        $(this).parent().children(".note").html()+'</div></div>');

        $("#noteWrapper").toggle();
        $("#noteWrapper .photos li").toggle();
        $("#noteWrapper .description span").toggle();

    });

    $("body").on("click",".photos img",function(e){
        console.log(e.target);

        var src = $(this).attr("src");
        
        $("#bigPhoto").remove();
        $(".screen-wrapper ul.photos").after('<div id="bigPhoto"><img src="'+src+'" alt=""></div>');
    });
});

function fillLatLng(e) {
    $("#latitude").val(e.latlng.lat);
    $("#longitude").val(e.latlng.lng);

    $("#addNoteWrapper").toggle();    

    $("#mapid").removeClass("crossheir");

    mymap.off('click');
}
