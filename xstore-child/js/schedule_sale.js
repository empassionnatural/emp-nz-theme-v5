
var schedule_sale;

jQuery(document).ready(function($){
    $('html').addClass(userBrowserAgent);

    schedule_sale = {

        init: function(){
            this.scheduleUpdate();
        },

        scheduleUpdate: function (){
            // console.log('test');
            $date = new Date();
            $day = $date.getUTCDate();
            $year = $date.getUTCFullYear();
            $month = $date.getUTCMonth();

            var current_time_gmt_unix = moment().unix();
            //console.log(current_time_gmt);
            //console.log(current_time_gmt_unix);
            //var black_friday = moment('2018-11-20 11:20').format('MM-DD-YYYY hh:mm a ZZ');
            //var aug_1_unix = moment( '11-19-2018' ).format('x');
            var black_friday_starts_unix = moment('2018-11-23 06:50').unix();
            var black_friday_ends_unix = moment('2018-11-25 24:00').unix();

            console.log(black_friday_starts_unix);

            if( current_time_gmt_unix < black_friday_starts_unix || current_time_gmt_unix > black_friday_ends_unix ){
                console.log( 'Get ready for Black friday sale!' );

                $('.single_add_to_cart_button, .add_to_cart_button').remove();

            } else {
                console.log( 'Black Friday Sale started!' );
            }

        },
    }

    schedule_sale.init();


});

