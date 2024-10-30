(function($) {
    "use strict";
    $(document).ready(function() {
        
        /*
        ========================================
            Attach File js
        ========================================
        */
        let uploadImage = document.querySelector(".uploadImage");
        let inputTag = document.querySelector(".inputTag");

        if(inputTag != null) {
            inputTag.addEventListener('change', ()=> {

                let inputTag = document.querySelector(".inputTag").files[0];

                uploadImage.innerText = inputTag.name;
            });
        };

        /*-------------------------------------------
        	Chat Contact show hide
        ------------------------------------------*/
        // $(document).on('click','.chatContact__btn', function() {
        //     let el = $(this);
        //     el.toggleClass('showChat');
        //     if(el.hasClass('showChat')) {
        //         el.find('i').text('close');
        //         $(this).closest('#chatContact').find('#chatContact__contents').addClass('showChat');
        //     }else{
        //         el.find('i').text('comment');
        //         el.closest('#chatContact').find('#chatContact__contents').removeClass('showChat');
        //     }
        // });
        
        // close chat 
        // $(document).on('click', '.close_chat', function() {
        //     $(this).closest('#chatContact').find('.chatContact__btn i').text('comment');
        //     $(this).closest('#chatContact').find('.chatContact__btn').removeClass('showChat');
        //     $(this).closest('#chatContact').find('#chatContact__contents').removeClass('showChat');
        // })
        
        //chat question text value
        // $(document).on('click', '.findFaq', function(event) {
        //     let el = $(this);
        //     let value = el.text();
        //     let parentWrap = el.closest('#chatContact__contents');
        //     parentWrap.find('#chatContact__faq, #chatContact__main').addClass('d-none');
        //     parentWrap.find('#chatContact__chat, #chatContact__team').removeClass('d-none');
        //     parentWrap.find('.replayfaqText').text(value);

        // });
        
        // Submit button click 
        // $(document).on('click', '#form_infoSubmit', function() {
        //     $(this).closest('#chatContact').find('#chatContact__form, #chatContact__login').addClass('d-none');

        //     $(this).closest('#chatContact').find('#chatContact__inner, #chatContact__faq, #chatContact__footer, #chatContact__main').removeClass('d-none');
        // });
        
        
        // emoji picker 
        new EmojiPicker({
            trigger: [
                {
                    selector: '.emoji_picker',
                    insertInto: '.emoji_show'
                }
            ],
            closeButton: true,
            //specialButtons: green
        });


    });

})(jQuery);