navigator.UserBrowser = (function(){
    var ua= navigator.userAgent, tem,
        M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
    if(/trident/i.test(M[1])){
        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
        return 'IE '+(tem[1] || '');
    }
    if(M[1]=== 'Chrome'){
        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
    }
    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
    return M.join(' ');
})();
var userBrowserAgent = navigator.UserBrowser;
var main_global;

jQuery(document).ready(function($){
    $('html').addClass(userBrowserAgent);

    main_global = {

        init: function(){
            this.selectCountry();
        },

        ajaxLoader: function(ele){
            var loader = '<span class="vc_icon_element-icon fa fa-refresh load-redirection ajax-loading"></span>';
            $(ele).prepend(loader);
        },
        selectCountry: function(){
            $('.select-country').val('NZ');
            $('.select-country').find('button img').attr('src', '/wp-content/uploads/2018/09/NZ_Flag-2.png');
            $('.select-country').find('button .sel-desc').text('NZ');
            $('.select-country').find('button').attr('title', 'NZ');

            $('.select-country').change(function(){
                var country_val = $(this).val();
                main_global.ajaxLoader('.bootstrap-select.select-country');
                var country_link;
                $('.bootstrap-select.select-country').find('button').attr('disabled', true);
                $('.bootstrap-select.select-country').find('button').addClass('btn-disabled');
                switch(country_val){
                    case 'AU':
                        location.href = 'https://empassion.com.au';
                        break;
                    case 'USA':
                        location.href = 'https://empassionnatural.com';
                        break;
                    default:
                        break;
                }
                //console.log(country_val);

            });
        },

        scheduleUpdate: function (){
            // console.log('test');
            $date = new Date();
            $day = $date.getUTCDate();
            $year = $date.getUTCFullYear();
            $month = $date.getUTCMonth();

            console.log( $month );
            console.log( $day );
            console.log( $year );
            console.log('============================');
            //var nz_time = moment().add(3,'h').format( 'MM-DD-YYYY hh:mm a ZZ' );
            //console.log(nz_time);


            var set_nz_tz = moment().utcOffset( "+12:00" ).format('x');

            var nz_time = moment().utcOffset( "+13:00" ).format('MM-DD-YYYY hh:mm a ZZ');
            console.log(nz_time);
            console.log('============================');
            // if( set_nz_tz >= aug_1_unix ) {
            //     //$('#shipping-fee').text('$8.50');
            //     console.log(aug_1_unix);
            //     console.log(set_nz_tz);
            // }

        },
    }

    main_global.init();

});

var openTabs = function(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
};

var openAccordion = function(evt , id1 , id2) {
    var y = document.getElementById(id1);
    var x = document.getElementById(id2);
    var acctDet = "acctDet";
    if (x.className.indexOf("wp-show") == -1) {
        y.className += " active";
        x.className += " wp-show";
        x.style.maxHeight = x.scrollHeight  + "px";
        if(id2 == acctDet){
            document.getElementById(id2).style.maxHeight = "1696px";
        }
    } else {
        y.className = y.className.replace(" active","")
        x.className = x.className.replace(" wp-show", "");
        x.style.maxHeight = null;
    }
}

var $win = $(window);

$win.on('scroll', function () {
    console.log($win.scrollTop());

    if ($win.scrollTop() < 1) {
        $('#id-floatingbutton').addClass('fbOut').removeClass('fbIn');
    }
    else {
        $('#id-floatingbutton').addClass('fbIn').removeClass('fbOut');
    }
});