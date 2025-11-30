/**
 * JS Cookie
 */
!function(e){var n;if("function"==typeof define&&define.amd&&(define(e),n=!0),"object"==typeof exports&&(module.exports=e(),n=!0),!n){var t=window.Cookies,o=window.Cookies=e();o.noConflict=function(){return window.Cookies=t,o}}}(function(){function e(){for(var e=0,n={};e<arguments.length;e++){var t=arguments[e];for(var o in t)n[o]=t[o]}return n}function n(e){return e.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent)}return function t(o){function r(){}function i(n,t,i){if("undefined"!=typeof document){"number"==typeof(i=e({path:"/"},r.defaults,i)).expires&&(i.expires=new Date(1*new Date+864e5*i.expires)),i.expires=i.expires?i.expires.toUTCString():"";try{var c=JSON.stringify(t);/^[\{\[]/.test(c)&&(t=c)}catch(e){}t=o.write?o.write(t,n):encodeURIComponent(String(t)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),n=encodeURIComponent(String(n)).replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent).replace(/[\(\)]/g,escape);var f="";for(var u in i)i[u]&&(f+="; "+u,!0!==i[u]&&(f+="="+i[u].split(";")[0]));return document.cookie=n+"="+t+f}}function c(e,t){if("undefined"!=typeof document){for(var r={},i=document.cookie?document.cookie.split("; "):[],c=0;c<i.length;c++){var f=i[c].split("="),u=f.slice(1).join("=");t||'"'!==u.charAt(0)||(u=u.slice(1,-1));try{var a=n(f[0]);if(u=(o.read||o)(u,a)||n(u),t)try{u=JSON.parse(u)}catch(e){}if(r[a]=u,e===a)break}catch(e){}}return e?r[e]:r}}return r.set=i,r.get=function(e){return c(e,!1)},r.getJSON=function(e){return c(e,!0)},r.remove=function(n,t){i(n,"",e(t,{expires:-1}))},r.defaults={},r.withConverter=t,r}(function(){})});

/**
 * Variables
 */

const currentLocation = window.location.href.split("#")[0];
const currentTime = Date.now();
let winWidth = window.innerWidth;
let winHeight = window.innerHeight;

const ffDiscounthuntCreateCORSRequest = (method, url) => {
	const xhr = new XMLHttpRequest();
	if ("withCredentials" in xhr) {
		xhr.open(method, url, true);
	} else if (typeof XDomainRequest != "undefined") {
		xhr = new XDomainRequest();
		xhr.open(method, url);
	} else {
		xhr = null;
	}
	return xhr;
}

const ffDiscounthuntCookieFound = (ffDiscounthuntSessionId) => {
  let cookieData = {
    cookieDate: Date.now(),
    sessionHuntId: ffDiscounthuntSessionId,
    codeFound: true,
    codeFoundDate: Date.now(),
    pageviews: 1,
    visitedPages: currentLocation
  }

  Cookies.set(`ffSessionHuntCookie_${ffDiscounthuntSessionId}`, cookieData, { expires: 365 });
  return;
}

/**
 * Handle discount hunt form submit
 */
const ffHandleDiscounthuntFormSubmit = (e) => {
  const ffDiscounthuntSubmitButton = document.querySelector("#ff-discounthunt-session-form__submit");
  const ffDiscounthuntSubmitButtonSpinner = ffDiscounthuntSubmitButton.querySelector("i");

  e.preventDefault();

  if(ffDiscounthuntSessionPreview){
    // preview, instantly display result page
    document.querySelector("#ff-discounthunt-session-modal__form-container").classList.add("ff-discounthunt-session--hidden");
    document.querySelector("#ff-discounthunt-session-modal__description").classList.add("ff-discounthunt-session--hidden");
    document.querySelector("#ff-discounthunt-session-modal__result").classList.remove("ff-discounthunt-session--hidden");
    document.querySelector("#ff-discounthunt-session-modal__result-discountcode").innerHTML = "PREVIEW CODE";
    return;
  }

  /**
   * Check required fields
   */
  const firstNameField = document.querySelector("#ff-discounthunt-session-form__first-name");
  const lastNameField = document.querySelector("#ff-discounthunt-session-form__last-name");
  const emailField = document.querySelector("#ff-discounthunt-session-form__email");
  const additionalInformationField = document.querySelector("#ff-discounthunt-session-form__additional");
  const legalCheckbox = document.querySelector("#ff-discounthunt-session-form__legal");

  const ffDiscounthuntHunterData = {
    huntSession: ffDiscounthuntSessionData.id,
    firstName: firstNameField.value,
    lastName: lastNameField.value,
    email: emailField.value,
    additionalInformation: additionalInformationField.value,
    legalAccepted: legalCheckbox.checked,
    nonce: ffDiscounthuntNonce,
    action: 'gifthuntfree_ajax_frontend_action',
    gifthuntfreeAction: 'collect',
  }

  let ffDiscounthuntErrorCount = 0;

  // first name check
  if(ffDiscounthuntHunterData.firstName.length < 1 && ffDiscounthuntSessionData.form_collect_first_name == 1){
    firstNameField.classList.add("ffDiscounthuntFieldError");
    ffDiscounthuntErrorCount++;
  } else {
    firstNameField.classList.remove("ffDiscounthuntFieldError");
  }

  // last name check
  if(ffDiscounthuntHunterData.lastName.length < 1 && ffDiscounthuntSessionData.form_collect_last_name == 1){
    lastNameField.classList.add("ffDiscounthuntFieldError");
    ffDiscounthuntErrorCount++;
  } else {
    lastNameField.classList.remove("ffDiscounthuntFieldError");
  }

  // email check
  if(ffDiscounthuntHunterData.email.length < 1 || (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(ffDiscounthuntHunterData.email))){
    emailField.classList.add("ffDiscounthuntFieldError");
    ffDiscounthuntErrorCount++;
  } else {
    emailField.classList.remove("ffDiscounthuntFieldError");
  }

  // legal checkbox check
  if(!legalCheckbox.checked){
    document.querySelector("#ff-discounthunt-session-form__group--legal a").classList.add("ffDiscounthuntFieldErrorText");
    ffDiscounthuntErrorCount++;
  } else {
    document.querySelector("#ff-discounthunt-session-form__group--legal a").classList.remove("ffDiscounthuntFieldErrorText");
  }

  if(ffDiscounthuntErrorCount){
    return;
  }

  /**
   * Disable button
   */
  ffDiscounthuntSubmitButton.disabled = true;

  /**
   * Display spinner
   */
  ffDiscounthuntSubmitButtonSpinner.classList.remove('ff-discounthunt-session--hidden');

  /**
   * Send data to backend
   */
  const ffDiscounthuntXHR = ffDiscounthuntCreateCORSRequest("POST", ffDiscounthuntApiEndpoint);
	if (!ffDiscounthuntXHR) {
		ffDiscounthuntSubmitButton.disabled = false;
		ffDiscounthuntSubmitButtonSpinner.classList.add('ff-discounthunt-session--hidden');
		alert("Something went wrong. Please refresh the page and try again.");
		return;
  }
  
  ffDiscounthuntXHR.setRequestHeader("X-Custom-Header", ffDiscounthuntNonce);
	ffDiscounthuntXHR.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

	ffDiscounthuntXHR.onload = function() {
    const response = JSON.parse(ffDiscounthuntXHR.responseText);
    ffDiscounthuntSubmitButton.disabled = false;
    ffDiscounthuntSubmitButtonSpinner.classList.add('ff-discounthunt-session--hidden');
    
		if (response.status === "error" || !response) {
      /**
       * Error
       * - alert error
       */
      alert(response.data);
      return;
    } else if(response.status === "closed"){
      /**
       * Hunt session closed or out of dicsount codes
       * - hide modal
       * - delete cookie
       */
      ffToggleDiscountHuntSessionPopup();
      Cookies.remove(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`);
		} else {
			/**
       * Success
       * - Display result message
       * - Display discount code
       * - Change cookie params
       */
      document.querySelector("#ff-discounthunt-session-modal__form-container").classList.add("ff-discounthunt-session--hidden");
      document.querySelector("#ff-discounthunt-session-modal__description").classList.add("ff-discounthunt-session--hidden");
      document.querySelector("#ff-discounthunt-session-modal__result").classList.remove("ff-discounthunt-session--hidden");
      document.querySelector("#ff-discounthunt-session-modal__result-discountcode").innerHTML = response.data;

      ffDiscounthuntCookieFound(ffDiscounthuntSessionData.id);

      return;
		}
	};

	ffDiscounthuntXHR.onerror = function() {
		ffDiscounthuntSubmitButton.disabled = false;
    ffDiscounthuntSubmitButtonSpinner.classList.add('ff-discounthunt-session--hidden');
		alert("Something went wrong. Please refresh the page and try again.");
		return;
	};

	ffDiscounthuntXHR.send(
		Object.keys(ffDiscounthuntHunterData)
			.map(function(k) {
				return encodeURIComponent(k) + "=" + encodeURIComponent(ffDiscounthuntHunterData[k]);
			})
			.join("&")
	);

}

/**
 * Toggle discount hunt session popup
 */
const ffToggleDiscountHuntSessionPopup = () => {
  if(!document.body.classList.contains('ffDiscounthuntPopupVisible')){
    /**
     * Show popup
     */
    let ffDiscountHuntSessionPopupContaner;

    /**
     * Add popup window content to modal
     */
    if(ffDiscounthuntSessionData.session_type == "custom_popup"){
      ffDiscountHuntSessionPopupContaner = document.getElementById("ff-discounthunt-popup-root");
      /**
       * Set cookie to found, because the user opened a custom popup window
       */
      ffDiscounthuntCookieFound(ffDiscounthuntSessionData.id);
    } else {
      ffDiscountHuntSessionPopupContaner = document.createElement('div');
      ffDiscountHuntSessionPopupContaner.style.cssText = `position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, .6); z-index: 999999; overflow-y: auto; overflow-x: hidden;`;
      ffDiscountHuntSessionPopupContaner.setAttribute('id', 'ff-discounthunt-popup-root');
      ffDiscountHuntSessionPopupContaner.innerHTML = ffDiscounthuntPopupWindow;
      /**
       * Event listener for form submit
       */
      ffDiscountHuntSessionPopupContaner.querySelector("#ff-discounthunt-session-modal__form").addEventListener("submit", ffHandleDiscounthuntFormSubmit);
    }



    /**
     * Add extra css to the head
     */
    if(!document.body.classList.contains('ffDiscounthuntPopupCSSAdded')){
      const ffDiscounthuntHead = document.head;
      let ffDiscounthuntExtraStyle = document.createElement('style');
      ffDiscounthuntHead.appendChild(ffDiscounthuntExtraStyle);
      ffDiscounthuntExtraStyle.type = 'text/css';
      if(ffDiscounthuntExtraStyle.styleSheet){
        ffDiscounthuntExtraStyle.styleSheet.cssText = ffDiscounthuntCSS;
      } else {
        ffDiscounthuntExtraStyle.appendChild(document.createTextNode(ffDiscounthuntCSS))
      }
      document.body.classList.add('ffDiscounthuntPopupCSSAdded');

      /**
       * Remove icon from site
       */
      let ffDiscounthuntIcon = null;
      const shortcodePlacement = (ffDiscounthuntSessionData.icon_placement && ffDiscounthuntSessionData.icon_placement == "shortcode") ? true : false;

      if( shortcodePlacement ){
        /**
         * Remove icon that was placed with a shortcode
         */
        ffDiscounthuntIcon = document.querySelector("a#ff-discounthunt-icon-container");
      } else {
        /**
         * Remove dinamically placed icon
         */
        ffDiscounthuntIcon = document.querySelector("#ff-discounthunt-present-icon");
      }
      ffDiscounthuntIcon.parentNode.removeChild(ffDiscounthuntIcon);
    }
    
    if(ffDiscounthuntSessionData.session_type == "custom_popup"){
      /**
       * Display custom popup window
       */
      ffDiscountHuntSessionPopupContaner.style.display = "block";
    } else {
      document.body.appendChild(ffDiscountHuntSessionPopupContaner);
    }

    document.body.classList.add('ffDiscounthuntPopupVisible');
  } else {
    /**
     * Hide popup
     */
    const ffDiscounhuntPopupRoot = document.querySelector("#ff-discounthunt-popup-root");
    ffDiscounhuntPopupRoot.parentNode.removeChild(ffDiscounhuntPopupRoot);
    document.body.classList.remove('ffDiscounthuntPopupVisible');
  }
}

/**
 * Check session hunt cookie
 * Create if doesn't exists,
 * Update if exists
 */
let cookieData = {
  cookieDate: currentTime,
  sessionHuntId: ffDiscounthuntSessionData.id,
  codeFound: false,
  pageviews: 1,
  visitedPages: currentLocation
}

/**
 * Add extra css to head
 */
const ffDiscounthuntSessionIconCss = () => {
  if(!document.body.classList.contains('ffDiscounthuntIconCSSAdded')){
    const ffDiscounthuntHead = document.head;
    let ffDiscounthuntExtraStyle = document.createElement('style');
    ffDiscounthuntHead.appendChild(ffDiscounthuntExtraStyle);
    ffDiscounthuntExtraStyle.type = 'text/css';
    if(ffDiscounthuntExtraStyle.styleSheet){
      ffDiscounthuntExtraStyle.styleSheet.cssText = ffDiscounthuntIconCSS;
    } else {
      ffDiscounthuntExtraStyle.appendChild(document.createTextNode(ffDiscounthuntIconCSS))
    }
    document.body.classList.add('ffDiscounthuntIconCSSAdded');
  }
}

const ffDiscounthuntInit = () => {
  const shortcodePlacement = (ffDiscounthuntSessionData.icon_placement && ffDiscounthuntSessionData.icon_placement == "shortcode") ? true : false;
  let shortcodeGifthuntIcon = document.querySelector("a#ff-discounthunt-icon-container");

  if (!Cookies.get(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`) && !ffDiscounthuntSessionPreview && !shortcodePlacement) {
    // doesn't exists, create it
    Cookies.set(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`, cookieData, { expires: 365 });
  } else {
    // exists or shortcode placement
    let cookieValue;

    // shortcode placement, create cookie
    if(shortcodePlacement && !Cookies.get(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`)){
      cookieValue = cookieData;
      Cookies.set(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`, cookieData, { expires: 365 });
    } else {
      cookieValue = Cookies.getJSON(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`);
    }
  
    if(!ffDiscounthuntSessionPreview){
      if(cookieValue.codeFound == true && ffDiscounthuntSessionData.allow_multiple_collect != 1){
        /**
         * Found a code previously
         * Multiple collect is not allowed
         * =====
         * Hide session icon if it's placed by shortcode
         */
        if( shortcodePlacement ){
          if( shortcodeGifthuntIcon ){
            shortcodeGifthuntIcon.remove();
          }
        }

        return;
      }
    }

    /**
     * Insert icon styling to the header
     */
    ffDiscounthuntSessionIconCss();

    /**
     * Don't display session icon with JS if placement is made by shortcode
     */
    if( shortcodePlacement ){
      if( shortcodeGifthuntIcon ){
        shortcodeGifthuntIcon.addEventListener('click', ffToggleDiscountHuntSessionPopup);
      }

      return;
    }
  
    const ffDiscountHuntPresentIcon = document.createElement('div');
    let iconTopPosition = 0;
    let iconLeftPosition = 0;

    if(ffDiscounthuntSessionData.icon_placement && ffDiscounthuntSessionData.icon_placement == "center"){
      iconTopPosition = Math.floor((winHeight - 80) / 2);
      iconLeftPosition = Math.floor((winWidth - 80) / 2);
    } else if(ffDiscounthuntSessionData.icon_placement && ffDiscounthuntSessionData.icon_placement == "bottom_left"){
      iconTopPosition = winHeight - 100;
      iconLeftPosition = 20;
    } else if(ffDiscounthuntSessionData.icon_placement && ffDiscounthuntSessionData.icon_placement == "bottom_right"){
      iconTopPosition = winHeight - 100;
      iconLeftPosition = winWidth - 100;
    } else {
      iconTopPosition = Math.floor(Math.random() * (winHeight - 120));
      iconLeftPosition = Math.floor(Math.random() * (winWidth - 120));
    }

    ffDiscountHuntPresentIcon.setAttribute('id', 'ff-discounthunt-present-icon');
    ffDiscountHuntPresentIcon.setAttribute('class', ffDiscounthuntSessionData.icon_animation || 'pop');
    ffDiscountHuntPresentIcon.style.cssText = `top: ${iconTopPosition}px; left: ${iconLeftPosition}px;`;
    if(ffDiscounthuntSessionData.icon == 99){
      ffDiscountHuntPresentIcon.innerHTML = `<a class="ff-discounthunt-icon-link"><div id="ff-discounthunt-icon-container"><img src="${decodeURIComponent(ffDiscounthuntSessionData.custom_icon)}" alt="Gift Hunt Icon" /></div></a>`;
    } else {
      ffDiscountHuntPresentIcon.innerHTML = `<a class="ff-discounthunt-icon-link"><div id="ff-discounthunt-icon-container"><img src="${ffDiscounthuntFrontendAssets}images/${ffDiscounthuntSessionData.icon}.svg" alt="Gift Hunt Icon" /></div></a>`;
    }
    ffDiscountHuntPresentIcon.addEventListener('click', ffToggleDiscountHuntSessionPopup);

    if(!ffDiscounthuntSessionPreview){
      // live
      if(ffDiscounthuntSessionData.display_type == "time" && Math.floor((Date.now() - cookieValue.cookieDate) / 1000) >= ffDiscounthuntSessionData.time_to_display){
        // user spent enough time on site to display present
        document.body.appendChild(ffDiscountHuntPresentIcon);
      } else if(ffDiscounthuntSessionData.display_type == "pageview" && cookieValue.pageviews >= ffDiscounthuntSessionData.pageview_to_display){
        // user viewed enough pages to display present
        document.body.appendChild(ffDiscountHuntPresentIcon);
      }

      // store visited pages
      const visitedPages = (!cookieValue.visitedPages.includes(currentLocation)) ? `${cookieValue.visitedPages}|${currentLocation}`: cookieValue.visitedPages;
    
      // count pageviews
      const visitedPagesArray = visitedPages.split("|")
      const pageviews = visitedPagesArray.length;
    
      cookieData = {
        cookieDate: cookieValue.cookieDate,
        sessionHuntId: cookieValue.sessionHuntId,
        codeFound: cookieValue.codeFound,
        pageviews: pageviews,
        visitedPages: visitedPages
      }
    
      Cookies.set(`ffSessionHuntCookie_${ffDiscounthuntSessionData.id}`, cookieData, { expires: 365 });
    } else {
      // preview, display icon
      document.body.appendChild(ffDiscountHuntPresentIcon);
    }
  }
}

window.onresize = () => {
  winWidth = window.innerWidth;
  winHeight = window.innerHeight;
}

document.body.addEventListener("click", (e) => {
  if(e.target.id == "ff-discounthunt-session-modal__close" || e.target.id == "ff-discounthunt-custom-modal__close"){
    ffToggleDiscountHuntSessionPopup();
  }
});

ffDiscounthuntInit();