

(function () {
    var photokit;
    window.addEventListener("message", function (event) {
        //1) Settings editor.
        if (event.data.type == "photokitsdk" && event.data.name == "editorLoaded") {//photokit editor loaded


            /*
                photokit.contentWindow.postMessage({type: 'photokitsdk',name:'saveimagetype',savetype:'postMessage'},'*');
            
                The image save mode "local" and "postMessage", the user clicks the save button or saves through the api:
                In local mode, the image will be saved locally.
                In postMessage mode, the image base64 data is passed to the parent window through possMessage.
            */


            toDataURL('https://cdn.pixabay.com/photo/2020/04/20/04/02/brick-5066282_960_720.jpg',
                function (dataUrl) {
                    photokit.contentWindow.postMessage({ type: 'photokitsdk', name: 'openimage', data: dataUrl, opentype: 0 }, '*');
                    //Open main image opentype:0


                    toDataURL('https://cdn.pixabay.com/photo/2020/03/13/04/06/handmade-4926871_960_720.jpg',
                        function (dataUrl2) {
                            //place image opentype:1
                            setTimeout(function () { photokit.contentWindow.postMessage({ type: 'photokitsdk', name: 'openimage', data: dataUrl2, opentype: 1 }, '*'); }, 100);
                            document.getElementById('pkeditor_saveimage').style = "display:block";
                        }
                    )
                }
            )

        }



        //2) In postMessage mode, monitor the saved image data.
        if (event.data.type == "photokitsdk" && event.data.name == "saveimage") {

            document.getElementById('pkeditor').innerHTML = "";
            document.getElementById('pkeditor_saveimage').innerHTML = "<img src='" + event.data.imagedata + "' alt='" + event.data.imagename + "'/>"



        }

    }, false);

    // 3) Invoke editor
    var iframe = document.createElement("iframe");
    iframe.id = "photokit";
    iframe.width = "100%";
    iframe.height = "100%";
    iframe.style = "border:0px;";
    iframe.src = "https://photokit.com/editor/?lang=en";
    // Supported languages: en, fr, de, ru, pt, es, ja, ko, zh;  
    // Replace with your affiliate link(become Photokit affiliate: https://cc.payproglobal.com/AffiliateSignup/8D9B5861FAACA09).
    document.getElementById('pkeditor').appendChild(iframe);
    photokit = document.getElementById('photokit')
    //Convert image to base64
    function toDataURL(src, callback, outputFormat) {
        var img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function () {
            var canvas = document.createElement('CANVAS');
            var ctx = canvas.getContext('2d');
            var dataURL;
            canvas.height = this.naturalHeight;
            canvas.width = this.naturalWidth;
            ctx.drawImage(this, 0, 0);
            dataURL = canvas.toDataURL(outputFormat);
            callback(dataURL);
        };
        img.src = src;
        if (img.complete || img.complete === undefined) {
            img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
            img.src = src;
        }
    }

    //upload image botton
    var uploadimage = document.getElementById('uploadimage')
    uploadimage.addEventListener("click", function () {

        photokit.contentWindow.postMessage({ type: 'photokitsdk', name: 'saveimagetype', savetype: 'postMessage' }, '*');
        /*
        The image save mode "local" and "postMessage", the user clicks the save button or saves through the api:
        In local mode, the image will be saved locally.
        In postMessage mode, the image base64 data is passed to the parent window through possMessage.
        */
        photokit.contentWindow.postMessage({ type: 'photokitsdk', name: 'saveimage' }, '*');
        photokit.contentWindow.postMessage({ type: 'photokitsdk', name: 'saveimagetype', savetype: 'local' }, '*');

    })





})();

  //photokit.com/integrations/ Insert Photokit into your webpage, with no ads, under your own brand...  please contact: support@photokit.com

