(function() {
    'use strict';
    var YfktnUtilReCaptcha = {
        _initialized: false,
        _loopingRun: false,
        _siteKey: '',
        _targetHtmlClass: 'g-recaptcha-response',
        _token: null,
        setSiteKey: function(key) {
            YfktnUtilReCaptcha._siteKey = key;
        },
        getSiteKey: function() {
            return YfktnUtilReCaptcha._siteKey;
        },
        setTargetHtmlClass: function(targetHtmlClass) {
            YfktnUtilReCaptcha._targetHtmlClass = targetHtmlClass;
        },
        getTargetHtmlClass: function() {
            return YfktnUtilReCaptcha._targetHtmlClass;
        },
        _setRecaptchaToken: function(theAction='submit') {
            if(YfktnUtilReCaptcha._siteKey) {
                grecaptcha.execute(YfktnUtilReCaptcha._siteKey, {action: theAction})
                    .then(function(token) {
                        YfktnUtilReCaptcha._token = token;
                        let inputs = document.querySelectorAll('input[type="hidden"][class="' + YfktnUtilReCaptcha._targetHtmlClass + '"]');
                        for(let i = 0; i < inputs.length; i++) {
                            inputs[i].value = token;
                        }
                    });
            } else {
                alert('No Site Key ReCaptcha Set!');
                console.error('No Site Key ReCaptcha Set!');
            }
        },
        _recaptchaTokenMustNotExpired: function(theAction='submit') {
            if(YfktnUtilReCaptcha.isInitialized() && !YfktnUtilReCaptcha._loopingRun) {
                YfktnUtilReCaptcha._loopingRun = true;
                setInterval(() => {
                    YfktnUtilReCaptcha._setRecaptchaToken(theAction);
                    // console.log('ReCaptcha Token Must Not Expired!');
                }, 120000); // 2 minutes = 120.000 ms expired                
            }
        },
        initRecaptcha: function(key = '', theAction='submit') {
            if(!YfktnUtilReCaptcha._initialized) {
                if(key.length > 0) {
                    YfktnUtilReCaptcha.setSiteKey(key);
                }
                YfktnUtilReCaptcha._initialized = true;
                YfktnUtilReCaptcha._setRecaptchaToken(theAction);
                YfktnUtilReCaptcha._recaptchaTokenMustNotExpired(theAction);
            } else {
                console.error('ReCaptcha Already Initialized!');
            }
        },
        isInitialized: function() {
            return YfktnUtilReCaptcha._initialized;
        },
    };
    if(!window.YfktnUtilReCaptcha) {
        window.YfktnUtilReCaptcha = YfktnUtilReCaptcha;
    }
})();