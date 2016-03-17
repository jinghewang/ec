/**
 * Created by hltravel on 16/3/2.
 */


jQuery.hlt = {

    empty: function (text) {
        if(text == null)
            return false;

        return $.trim(text).length == 0;
    },
}