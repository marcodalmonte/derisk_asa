function adaptImage(url, div, width, height)
{
    var img = new Image();
    img.src = url;
    
    img.onload = function() {
        jQuery(div).html('<img src="' + url + '" />');

        var ref_img = jQuery(div).children('img');

        var o_w = ref_img.get(0).width;
        var o_h = ref_img.get(0).height;

        var isiPad = navigator.userAgent.match(/iPad/i) != null;
        var ua = navigator.userAgent;
        var isiPad = /iPad/i.test(ua) || /iPhone OS 3_1_2/i.test(ua) || /iPhone OS 3_2_2/i.test(ua);

        if (isiPad) {
            o_w = this.width;
            o_h = this.height;
        }

        var f_w = width;
        var f_h = height;

        var old_fw = width;
        var old_fh = height;

        if ((o_w > f_w) || (o_h > f_h)) {
            if ((f_w / f_h) > (o_w / o_h)) {
                f_w = parseInt((o_w * f_h) / o_h);
            } else if ((f_w / f_h) < (o_w / o_h)) {
                f_h = parseInt((o_h * f_w) / o_w);
            }

            jQuery(ref_img.get(0)).css('width',f_w + "px");
            jQuery(ref_img.get(0)).css('height',f_h + "px");
        } else {
            f_w = o_w;
            f_h = o_h;
        }

        jQuery(ref_img.get(0)).css('margin-left',((old_fw - f_w) / 2) + 'px');
        jQuery(ref_img.get(0)).css('margin-top',((old_fh - f_h) / 2) + 'px');
        jQuery(ref_img.get(0)).css('image-orientation','from-image');
        jQuery(ref_img.get(0)).css('visibility','visible');
    }      
}

function invertDate(curdate)
{
    if (curdate == "") {
        return "";
    };
    
    var broken = curdate.split('/');
    
    return broken[2] + "-" + broken[1] + "-" + broken[0];
}
