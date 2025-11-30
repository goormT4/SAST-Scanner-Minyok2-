const saveMailchimpSettingsButtons = document.querySelectorAll(".gifthunt-save-mailchimp-settings");

/**
 * Get the list of managed Mailchimp lists
 */
const apiKeyField = document.querySelector("#api_key");
const getListsButton = document.querySelector("#api_key_submit");

const resultModal = document.querySelector(".gifthunt-form-result__modal");
const resultModalError = document.querySelector(".gifthunt-form-result--error");
const resultModalErrorContent = resultModalError.querySelector(".gifthunt-form-result__content");
const resultModalSuccess = document.querySelector(".gifthunt-form-result--success");
const resultModalSuccessContent = resultModalSuccess.querySelector(".gifthunt-form-result__content");

const listSelect = document.getElementById("list_id");
const listSettingsBlock = document.getElementById("list_settings");

const statusBlock = document.getElementById("integration_status");
const selectedListNameContainer = document.getElementById("selected_list_name");

const getLists = ( e ) => {
  e.preventDefault();
  const apiKey = apiKeyField.value;
  if( !apiKey ){
    apiKeyField.focus();
    alert("Please, provide and API Key");
    return;
  }

  getListOptions( apiKey );
}

const getListOptions = ( apiKey ) => {
  let gifthuntAction = "mailchimp_lists";
  const data = {
    'nonce': gifthuntNonce,
    'action': 'gifthunt_action',
    gifthuntAction,
    apiKey
  }

  // disable button
  getListsButton.disabled = true;

  // display loading aniamtion
  getListsButton.querySelector("i").classList.remove("hidden");

  jQuery.post(ajaxurl, data, (response) => {
    // enable button
    getListsButton.disabled = false;

    // hide loading animation
    getListsButton.querySelector("i").classList.add("hidden");

    /**
     * Check for error
     */
    if(response.status){
      /**
       * Display response status and title
       */
      resultModalErrorContent.innerHTML = `<strong>${response.status} - ${response.title}</strong><br /><br />${response.detail}`;
      resultModal.classList.remove("hidden");
      resultModalError.classList.remove("hidden");
    } else if(response.lists){
      displayListSelect(response.lists);
      listSettingsBlock.classList.remove("hidden");
    }
  });
}

/**
 * Update list name in hidden field
 */
const listNameField = document.getElementById("list_name");
const updateListName = ( e ) => {
  const listName = listSelect.options[listSelect.selectedIndex].text;
  listNameField.value = listName;
  selectedListNameContainer.textContent = listName;
}

listSelect.addEventListener("change", updateListName);

getListsButton.addEventListener("click", getLists);

/**
 * Get merge fields for selected list
 */
const getMergeFieldsButton = document.getElementById("list_submit");
const mergeFieldFirstName = document.getElementById("fname");
const mergeFieldLastName = document.getElementById("lname");
const mergeFieldAdditional = document.getElementById("additional");
const mergeFieldSettingsBlock = document.getElementById("merge_fields");

const getMergeFields = ( e ) => {
  e.preventDefault();

  // check api key
  const apiKey = apiKeyField.value;
  if( !apiKey ){
    apiKeyField.focus();
    alert("Please, provide and API Key");
    return;
  }

  // check list
  const listId = listSelect.value;
  if( !listId ){
    listSelect.focus();
    alert("Please, select a list");
    return;
  }

  updateListName();

  getMergeFieldOptions( apiKey, listId );

  // display status section
}

const getMergeFieldOptions = ( apiKey, listId ) => {
  let gifthuntAction = "mailchimp_merge_fields";
  const data = {
    'nonce': gifthuntNonce,
    'action': 'gifthunt_action',
    gifthuntAction,
    apiKey,
    listId
  }

  // disable button
  getMergeFieldsButton.disabled = true;

  // display loading aniamtion
  getMergeFieldsButton.querySelector("i").classList.remove("hidden");

  jQuery.post(ajaxurl, data, (response) => {
    // enable button
    getMergeFieldsButton.disabled = false;

    // hide loading animation
    getMergeFieldsButton.querySelector("i").classList.add("hidden");

    /**
     * Check for error
     */
    if(response.status){
      /**
       * Display response status and title
       */
      resultModalErrorContent.innerHTML = `<strong>${response.status} - ${response.title}</strong><br /><br />${response.detail}`;
      resultModal.classList.remove("hidden");
      resultModalError.classList.remove("hidden");
    } else if(response.merge_fields){
      mergeFieldFirstName.innerHTML = displayMergeFieldSelect(response.merge_fields, "fname");
      mergeFieldLastName.innerHTML = displayMergeFieldSelect(response.merge_fields, "lname");
      mergeFieldAdditional.innerHTML = displayMergeFieldSelect(response.merge_fields, "additional");
      mergeFieldSettingsBlock.classList.remove("hidden");

      // display save settings button
      saveMailchimpSettingsButtons.forEach(button => {
        button.classList.remove("hidden");
      });

      /**
       * Display status section
       */
      statusBlock.classList.remove("hidden");
    }
  });
}

getMergeFieldsButton.addEventListener("click", getMergeFields);

/**
 * Close modal
 */
document.querySelectorAll(".gifthunt-form-result__close-button").forEach(closeButton => {
  closeButton.addEventListener("click", () => {
    document.querySelectorAll(".gifthunt-form-result__modal").forEach(gifthuntModal => {
      gifthuntModal.classList.add("hidden");
    });
  });
});

/**
 * Save mailchimp integration settings
 */
const saveIntegrationSettings = ( e ) => {
  e.preventDefault();

  // check api key
  const apiKey = apiKeyField.value;
  if( !apiKey ){
    apiKeyField.focus();
    alert("Please, provide and API Key");
    return;
  }

  // check list
  const listId = listSelect.value;
  if( !listId ){
    listSelect.focus();
    alert("Please, select a list");
    return;
  }

  // check merge fields
  const fname = mergeFieldFirstName.value;
  const lname = mergeFieldLastName.value;
  const additional = mergeFieldAdditional.value;

  if( !fname || !lname || !additional ){
    alert("Please, select a value for all merge fields");
    return;
  }

  let active = document.querySelector("#active").checked;

  // disable buttons
  saveMailchimpSettingsButtons.forEach(button => {
    button.disabled = true;
    button.querySelector("i").classList.remove("hidden");
  });

  const listName = listNameField.value;

  let gifthuntAction = "mailchimp_save_settings";
  const data = {
    'nonce': gifthuntNonce,
    'action': 'gifthunt_action',
    gifthuntAction,
    apiKey,
    listId,
    listName,
    fname,
    lname,
    additional,
    active
  }

  jQuery.post(ajaxurl, data, (response) => {
    
    // enable buttons, hide loading anim
    saveMailchimpSettingsButtons.forEach(button => {
      button.disabled = false;
      button.querySelector("i").classList.add("hidden");
    });

    /**
     * Check for error
     */
    if(response.status == "error"){
      /**
       * Display response status and title
       */
      resultModalErrorContent.innerHTML = `<strong>Error</strong><br />${response.data}`;
      resultModal.classList.remove("hidden");
      resultModalError.classList.remove("hidden");
      /**
       * Hide previously displayed success message, if there were any
       */
      resultModalSuccess.classList.add("hidden");
    } else if(response.status == "success"){
      /**
       * Display success message
       */
      let resultModalContent = "";
      if( active ) {
        resultModalContent = `<strong>Success</strong><br />Your Mailchimp settings have been saved successfully. From now, the collected user information will be sent to the selected Mailchimp list automatically.<br /><br /><strong>⚠️ Important</strong><br />Previously collected user information won't be sent to your list only the new ones. If you collected some user information previously and would like to keep your lists in sync, export your hunters and import the collected data to Mailchimp.`;
      } else {
        resultModalContent = `<strong>Success</strong><br />Your Mailchimp settings have been saved successfully but your integration is <strong>not active</strong> which means that the collected user information won't be sent to your Mailchimp account.<br />To start sending the collected information to Mailchimp, turn on the integration at "Status" section of this page.`;
      }

      /**
       * Remove Mailchimp notice if integration is inactive
       */
      const mailchimpNotice = document.querySelector("#mailchimp-notice");

      if( mailchimpNotice && !active){
        mailchimpNotice.classList.add("hidden");
      }

      resultModalSuccessContent.innerHTML = resultModalContent;
      resultModal.classList.remove("hidden");
      resultModalSuccess.classList.remove("hidden");

      /**
       * Hide previously displayed error messages if there were any
       */
      resultModalError.classList.add("hidden");
    }
  });
}

saveMailchimpSettingsButtons.forEach(button => {
  button.addEventListener("click", saveIntegrationSettings);
});

const loadDefaultValues = () => {
  /**
   * Load list options
   */
  if( defaultApiKey ){
    getListOptions( defaultApiKey );
  }

  /**
   * Load merge field options
   */
  if( defaultApiKey && defaultListId ) {
    getMergeFieldOptions( defaultApiKey, defaultListId );
  }
}

loadDefaultValues();

const displayListSelect = (lists) => {
  /**
   * Display list selector section and load list to the select item
   */
  let listOptions = "";
  lists.forEach(list => {
    if( defaultListId && defaultListId == list.id ){
      listOptions += `<option selected="selected" value="${list.id}">${list.name}</option>`
    } else {
      listOptions += `<option value="${list.id}">${list.name}</option>`
    }
  });

  listSelect.innerHTML = listOptions;
}

const displayMergeFieldSelect = (fields, currentField) => {
  /**
   * Display merge field lists
   */
  let mergeFields = "";
  fields.forEach(field => {
    if( currentField == "fname" ){
      if( defaultFname && defaultFname == field.tag){
        mergeFields += `<option selected="selected" value="${field.tag}">${field.tag} - ${field.name}</option>`
      } else {
        mergeFields += `<option value="${field.tag}">${field.tag} - ${field.name}</option>`
      }
    } else if ( currentField == "lname" ) {
      if( defaultLname && defaultLname == field.tag){
        mergeFields += `<option selected="selected" value="${field.tag}">${field.tag} - ${field.name}</option>`
      } else {
        mergeFields += `<option value="${field.tag}">${field.tag} - ${field.name}</option>`
      }
    } else if ( currentField == "additional" ) {
      if( defaultAdditional && defaultAdditional == field.tag){
        mergeFields += `<option selected="selected" value="${field.tag}">${field.tag} - ${field.name}</option>`
      } else {
        mergeFields += `<option value="${field.tag}">${field.tag} - ${field.name}</option>`
      }
    } else {
      mergeFields += `<option value="${field.tag}">${field.tag} - ${field.name}</option>`
    }
  });

  return mergeFields;
}

/**
 * Test integration settings
 */
const testIntegrationSubmitButton = document.getElementById("test_integration_submit");
const testEmailAddressField = document.getElementById("test_email_address");
const testFirstNameField = document.getElementById("test_first_name");
const testLastNameField = document.getElementById("test_last_name");
const testAdditionalInformationField = document.getElementById("test_additional_information");

const submitIntegrationTest = (e) => {
  e.preventDefault();

  /**
   * Get test values
   */
  const testEmailAddress = testEmailAddressField.value;
  const testFirstName = testFirstNameField.value;
  const testLastName = testLastNameField.value;
  const testAdditionalInformation = testAdditionalInformationField.value;

  if( !testEmailAddress || !testFirstName || !testLastName || !testAdditionalInformation ){
    alert("Please add some value to all test fields (email, first name, last name, additional information)");
    return;
  }

  /**
   * Check integration values
   */
  // check api key
  const apiKey = apiKeyField.value;
  if( !apiKey ){
    apiKeyField.focus();
    alert("Please, provide and API Key to test your integration");
    return;
  }

  // check list
  const listId = listSelect.value;
  if( !listId ){
    listSelect.focus();
    alert("Please, select a list to test your integration");
    return;
  }

  // check merge fields
  const fname = mergeFieldFirstName.value;
  const lname = mergeFieldLastName.value;
  const additional = mergeFieldAdditional.value;

  if( !fname || !lname || !additional ){
    alert("Please, select a value for all merge fields to test your integration");
    return;
  }

  // disable button
  testIntegrationSubmitButton.disabled = true;
  testIntegrationSubmitButton.querySelector("i").classList.remove("hidden");

  let gifthuntAction = "mailchimp_test_integration";
  const data = {
    'nonce': gifthuntNonce,
    'action': 'gifthunt_action',
    gifthuntAction,
    apiKey,
    listId,
    fname,
    lname,
    additional,
    testEmailAddress,
    testFirstName,
    testLastName,
    testAdditionalInformation
  }

  jQuery.post(ajaxurl, data, (response) => {
    
    // enable button, hide loading anim
    testIntegrationSubmitButton.disabled = false;
    testIntegrationSubmitButton.querySelector("i").classList.add("hidden");

    /**
     * Check for error
     */
    if(response.status == "error"){
      /**
       * Display response status and title
       */
      resultModalErrorContent.innerHTML = `<strong>Error</strong><br />${response.data}`;
      resultModal.classList.remove("hidden");
      resultModalError.classList.remove("hidden");
      /**
       * Hide previously displayed success message, if there were any
       */
      resultModalSuccess.classList.add("hidden");
    } else if(response.status == "success"){
      /**
       * Display success message
       */
      resultModalSuccessContent.innerHTML = `<strong>Success</strong><br /><br />Gift Hunt was able to connect to your Mailchimp account and added the test information to the selected audience list.`;
      resultModal.classList.remove("hidden");
      resultModalSuccess.classList.remove("hidden");

      /**
       * Hide previously displayed error messages if there were any
       */
      resultModalError.classList.add("hidden");
    }
  });

}

testIntegrationSubmitButton.addEventListener("click", submitIntegrationTest);