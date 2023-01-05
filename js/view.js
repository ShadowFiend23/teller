$(function(){
    let tellers = {};
    let sound = new Howl({
        src: ['audio/tellerNotif.mp3']
    });

    const audioNotif = document.getElementById("tellerNotif");
    $('.tellerStatus').each(function() {
        tellers[$(this).prop('id')] = $(this).html();
    });

    function askObserver(tellers) {
        $.ajax({
            type: "GET",
            url: "ajax/observer.php",
            success: function(data){
            let newTellers = $.parseJSON(data);
                if(JSON.stringify(tellers) !== JSON.stringify(newTellers)){
                    for (let key of Object.keys(newTellers)) {
                        if(tellers[key] !== newTellers[key]){
                            $("#"+key).html(newTellers[key]);
                            $("#"+key).addClass("active");
                            setTimeout(() => {
                                sound.play();
                            }, 100);
                            setTimeout(() => {
                                $("#"+key).removeClass("active");
                            },2000)
                        }
                    }
                }
                setTimeout(askObserver, 1000, newTellers);
                
            }
        });
    }

    askObserver(tellers);
})