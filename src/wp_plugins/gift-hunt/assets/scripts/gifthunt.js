/**
 * Datepickers - visible from and to fields
 */
jQuery(document).ready(function () {
	jQuery("#visible_from").datepicker({
		dateFormat: "yy-mm-dd",
		minDate: 0,
	});

	jQuery("#visible_to").datepicker({
		dateFormat: "yy-mm-dd",
		minDate: 0,
	});
});

function isVisible(e) {
	return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length);
}

/**
 * Show - hide elements on value change
 */
document.querySelectorAll("input[name='display_type']").forEach((displayTypeRadio) => {
	displayTypeRadio.addEventListener("change", toggleDisplayTypeFields);
});

function toggleDisplayTypeFields() {
	if (this.value == "time") {
		// hide pageview section
		document.querySelector("#pageview_to_display_section").classList.add("hidden");
		// display time section
		document.querySelector("#time_to_display_section").classList.remove("hidden");
	} else {
		// hide time section
		document.querySelector("#time_to_display_section").classList.add("hidden");
		// display pageview section
		document.querySelector("#pageview_to_display_section").classList.remove("hidden");
	}
}

document.querySelector("#form_collect_first_name").addEventListener("change", (e) => {
	if (e.target.checked) {
		// display first name label field
		document.querySelector("#form_label_first_name_section").classList.remove("hidden");
	} else {
		// hide first name label field
		document.querySelector("#form_label_first_name_section").classList.add("hidden");
	}
});

document.querySelector("#form_collect_last_name").addEventListener("change", (e) => {
	if (e.target.checked) {
		// display last name label field
		document.querySelector("#form_label_last_name_section").classList.remove("hidden");
	} else {
		// hide last name label field
		document.querySelector("#form_label_last_name_section").classList.add("hidden");
	}
});

document.querySelector("#form_collect_additional_information").addEventListener("change", (e) => {
	if (e.target.checked) {
		// display additional information label field
		document.querySelector("#form_label_additional_information_section").classList.remove("hidden");
	} else {
		// hide additional information label field
		document.querySelector("#form_label_additional_information_section").classList.add("hidden");
	}
});

document.querySelectorAll("input[name='gift_type']").forEach((giftType) => {
	giftType.addEventListener("change", toggelGiftSections);
});

document.querySelector("#success_email_send").addEventListener("change", (e) => {
	if (e.target.checked) {
		// show success email settings sections
		document.querySelectorAll(".success-email-section").forEach((section) => {
			section.classList.remove("hidden");
		});
	} else {
		// hide success email settings sections
		document.querySelectorAll(".success-email-section").forEach((section) => {
			section.classList.add("hidden");
		});
	}
});

function toggelGiftSections() {
	if (this.value == "oneMany") {
		// hide more gifts section
		document.querySelector("#gifthunt-card--more-gifts").classList.add("hidden");
		// display one gift section
		document.querySelector("#gifthunt-card--one-gift").classList.remove("hidden");
	} else {
		// hide one gift section
		document.querySelector("#gifthunt-card--one-gift").classList.add("hidden");
		// display more gifts section
		document.querySelector("#gifthunt-card--more-gifts").classList.remove("hidden");
	}
}

document.querySelector("#active").addEventListener("change", (e) => {
	if (e.target.checked) {
		// display date fields
		document.querySelector("#visible-from-section").classList.remove("hidden");
		document.querySelector("#visible-to-section").classList.remove("hidden");
	} else {
		// hide date fields
		document.querySelector("#visible-from-section").classList.add("hidden");
		document.querySelector("#visible-to-section").classList.add("hidden");
	}
});

/**
 * Add new gift section
 */
document.querySelector("#gifthunt-add-gift-button").addEventListener("click", addNewGiftField);

function addNewGiftField() {
	// get highest gift index
	let giftIndex = 1;
	document.querySelectorAll("#gifthunt-card__section--gift-list .gift-item").forEach((giftItem) => {
		const currentIndex = parseInt(giftItem.dataset.giftId);
		if (currentIndex >= giftIndex) {
			giftIndex = currentIndex + 1;
		}
	});

	let giftFieldTemplate = document
		.querySelector("#gift-field-template")
		.textContent.replace(/%GIFTID%/gi, giftIndex)
		.replace(/%GIFTDBID%/gi, "0")
		.replace(/%GIFTGIFT%/gi, "");
	let newField = document.createRange().createContextualFragment(giftFieldTemplate);

	document.querySelector("#gifthunt-card__section--gift-list").appendChild(newField);
}

/**
 * Remove gift item
 */
document.addEventListener("click", (e) => {
	if (e.target.classList.contains("gifthunt__gift-item-remove-button")) {
		document.querySelector(`#gift-item-${e.target.dataset.giftId}`).remove();
	}
});

/**
 * Check required fields on blur
 */
const gifthuntRequiredFields = document.querySelectorAll("form [required]");
gifthuntRequiredFields.forEach((requiredField) => {
	valueCheck(requiredField);
	requiredField.addEventListener("blur", () => valueCheck(requiredField));
});

function valueCheck(element) {
	if (!element.value) {
		if (element.classList.contains("gifthunt-field-error")) {
			return;
		}

		// display error message
		element.classList.add("gifthunt-field-error");

		let errorMessage = document.createElement("div");
		errorMessage.classList.add("gifthunt-error-message");
		errorMessage.id = `gifthunt-error-message-${element.id}`;
		errorMessage.innerText = element.dataset.gifthuntError;

		element.parentNode.insertBefore(errorMessage, element.nextSibling);
	} else {
		// remove error message if exists
		if (document.querySelector(`#gifthunt-error-message-${element.id}`)) {
			element.classList.remove("gifthunt-field-error");
			document.querySelector(`#gifthunt-error-message-${element.id}`).remove();
		}
	}
}

/**
 * Display or hide error for empty editors
 */
function toggleEditorError(editorId, action) {
	if (action == "display") {
		// display editor error message
		document.querySelector(`#${editorId}-error-message`).classList.remove("hidden");
	} else {
		// hide editor error message
		document.querySelector(`#${editorId}-error-message`).classList.add("hidden");
	}
}

function toggleIconError(action) {
	if (action == "display") {
		document.querySelector("#icon_error-message").classList.remove("hidden");
	} else {
		document.querySelector("#icon_error-message").classList.add("hidden");
	}
}

function toggleIconPlacementError(action) {
	if (action == "display") {
		document.querySelector("#icon_placement_error-message").classList.remove("hidden");
	} else {
		document.querySelector("#icon_placement_error-message").classList.add("hidden");
	}
}

function toggleIconAnimationError(action) {
	if (action == "display") {
		document.querySelector("#icon_animation_error-message").classList.remove("hidden");
	} else {
		document.querySelector("#icon_animation_error-message").classList.add("hidden");
	}
}

function toggleVisibleToVisitorsError(action) {
	if (action == "display") {
		document.querySelector("#visible_to_visitors_error-message").classList.remove("hidden");
	} else {
		document.querySelector("#visible_to_visitors_error-message").classList.add("hidden");
	}
}

function togglePopupDesignError(action) {
	if (action == "display") {
		document.querySelector("#popup_design_error-message").classList.remove("hidden");
	} else {
		document.querySelector("#popup_design_error-message").classList.add("hidden");
	}
}

function toggleGiftTypeError(action) {
	if (action == "display") {
		document.querySelector("#gift_type-error-message").classList.remove("hidden");
	} else {
		document.querySelector("#gift_type-error-message").classList.add("hidden");
	}
}

/**
 * Check if the form is ready to save
 */
function checkReadyToSave() {
	let gifthuntSessionData = {};
	let errorCount = 0;
	let errorMessages = [];

	// check basic fields
	let requiredBasicFields = ["#name"];

	/**
	 * Check session type and change required fields based on that
	 */
	const sessionType = document.querySelector(`input[name="session_type"]:checked`).value;
	if (sessionType == "default") {
		requiredBasicFields.push("#popup_title", "#form_label_email", "#form_label_legal", "#form_legal_url", "#popup_button", "#success_email_sender_name", "#success_email_sender_email", "#success_email_subject");
	} else if (sessionType == "custom_popup") {
		requiredBasicFields.push("#custom_popup_close_button_text");
	}

	gifthuntSessionData["session_type"] = sessionType;

	requiredBasicFields.forEach((requiredField) => {
		const currentField = document.querySelector(requiredField);
		if (!currentField.value) {
			errorCount++;
			valueCheck(currentField);
			errorMessages.push(currentField.dataset.gifthuntError);
		} else {
			valueCheck(currentField);
		}

		gifthuntSessionData[currentField.id] = currentField.value;
	});

	// check wysiwyg fields
	let requiredEditorFields = ["popup_body", "popup_submit_body", "success_email_body"];
	if (sessionType == "custom_popup") {
		requiredEditorFields = ["custom_popup_content"];
	}

	requiredEditorFields.forEach((requiredField) => {
		let currentValue = "";
		if (isVisible(document.querySelector(`#${requiredField}`))) {
			/**
			 * Text editor
			 */
			currentValue = document.querySelector(`#${requiredField}`).value;
		} else {
			/**
			 * Visual editor
			 */
			currentValue = tinyMCE.editors[requiredField].getContent();
		}

		gifthuntSessionData[requiredField] = currentValue;

		if (currentValue.replace(/<[^>]*>?/gm, "") == "") {
			errorCount++;
			toggleEditorError(requiredField, "display");
			errorMessages.push(document.querySelector(`#${requiredField}-error-message`).textContent);
		} else {
			toggleEditorError(requiredField, "hide");
		}
	});

	// check session icon
	const icon = document.querySelector("input[name='icon']:checked");
	const customIcon = document.querySelector("#custom_icon");
	if (!icon) {
		errorCount++;
		toggleIconError("display");
		errorMessages.push(document.querySelector("#icon_error-message").textContent);
	} else {
		toggleIconError("hide");
		gifthuntSessionData["icon"] = icon.value;

		if (gifthuntSessionData["icon"] == 99) {
			// custom icon, check uploaded file
			if (customIcon.value == "") {
				errorCount++;
				toggleIconError("display");
			} else {
				toggleIconError("hide");
				gifthuntSessionData["custom_icon"] = customIcon.value;
			}
		}
	}

	// check icon placement
	const iconPlacement = document.querySelector("input[name='icon_placement']:checked");
	if (!iconPlacement) {
		errorCount++;
		toggleIconPlacementError("display");
		errorMessages.push(document.querySelector("#icon_placement_error-message").textContent);
	} else {
		toggleIconPlacementError("hide");
		gifthuntSessionData["icon_placement"] = iconPlacement.value;
	}

	// check animation
	const iconAnimation = document.querySelector("input[name='icon_animation']:checked");
	if (!iconAnimation) {
		errorCount++;
		toggleIconAnimationError("display");
		errorMessages.push(document.querySelector("#icon_animation_error-message").textContent);
	} else {
		toggleIconAnimationError("hide");
		gifthuntSessionData["icon_animation"] = iconAnimation.value;
	}

	// check visitor restrictions
	const visibleToVisitors = document.querySelector("input[name='visible_to_visitors']:checked");
	if (!visibleToVisitors) {
		errorCount++;
		toggleVisibleToVisitorsError("display");
		errorMessages.push(document.querySelector("#visible_to_visitors_error-message").textContent);
	} else {
		toggleVisibleToVisitorsError("hide");
		gifthuntSessionData["visible_to_visitors"] = visibleToVisitors.value;
	}

	// check fields with logic
	const displayType = document.querySelector("input[name='display_type']:checked").value;
	if (displayType == "time") {
		// check time field
		const timeToDisplay = document.querySelector("#time_to_display");
		if (!timeToDisplay.value) {
			errorCount++;
			valueCheck(timeToDisplay);
			errorMessages.push(timeToDisplay.dataset.gifthuntError);
		} else {
			gifthuntSessionData["time_to_display"] = timeToDisplay.value;
			gifthuntSessionData["display_type"] = "time";
		}

		// add default value to pageview field
		document.querySelector("#pageview_to_display").value = "5";
	} else {
		// check pageview field
		const pageViewToDisplay = document.querySelector("#pageview_to_display");
		if (!pageViewToDisplay.value) {
			errorCount++;
			valueCheck(pageViewToDisplay);
			errorMessages.push(pageViewToDisplay.dataset.gifthuntError);
		} else {
			gifthuntSessionData["pageview_to_display"] = pageViewToDisplay.value;
			gifthuntSessionData["display_type"] = "pageview";
		}

		// add default value to time field
		document.querySelector("#time_to_display").value = "90";
	}

	// check gift type
	const giftType = document.querySelector("input[name='gift_type']:checked");
	if (!giftType && sessionType == "default") {
		errorCount++;
		toggleGiftTypeError("display");
		errorMessages.push(document.querySelector("#gift_type-error-message").textContent);
	} else {
		toggleGiftTypeError("hide");
		gifthuntSessionData["gift_type"] = giftType.value;
	}

	// check gifts based on gift type
	if (gifthuntSessionData["gift_type"] && gifthuntSessionData["gift_type"] == "oneMany" && sessionType == "default") {
		// one gift for everyone
		const gift = document.querySelector("#gift");
		if (!gift.value) {
			errorCount++;
			valueCheck(gift);
			errorMessages.push(gift.dataset.gifthuntError);
		} else {
			valueCheck(gift);
			gifthuntSessionData["gift"] = gift.value;
		}
	} else if (gifthuntSessionData["gift_type"] && gifthuntSessionData["gift_type"] != "oneMany" && sessionType == "default") {
		// multiple gifts
		let giftItems = [];
		let giftItemsCount = 0;
		const giftsError = document.querySelector("#gifts-error-message");

		document.querySelectorAll(".gift-item").forEach((giftItem) => {
			const currentGiftItem = giftItem.querySelector("input");
			if (currentGiftItem.value) {
				giftItems.push({
					value: currentGiftItem.value,
					dbid: currentGiftItem.dataset.giftDbId,
				});

				giftItemsCount++;
			}
		});

		if (!giftItemsCount) {
			errorCount++;
			errorMessages.push(giftsError.textContent);
			giftsError.classList.remove("hidden");
		} else {
			gifthuntSessionData["gifts"] = giftItems;
			giftsError.classList.add("hidden");
		}
	}

	// checkbox values
	const checkboxFields = ["allow_multiple_collect", "form_collect_first_name", "form_collect_last_name", "form_collect_additional_information", "success_email_send"];
	checkboxFields.forEach((checkboxField) => {
		gifthuntSessionData[checkboxField] = document.querySelector(`#${checkboxField}`).checked ? 1 : 0;
	});

	// check values for selected checkboxes
	const checkboxFieldsNames = ["form_collect_first_name", "form_collect_last_name", "form_collect_additional_information"];
	checkboxFieldsNames.forEach((checkboxFieldName) => {
		const currentFieldId = checkboxFieldName.replace("collect", "label");
		const currentField = document.querySelector(`#${currentFieldId}`);

		if (gifthuntSessionData[checkboxFieldName] && !currentField.value && sessionType == "default") {
			errorCount++;
			valueCheck(currentField);
			errorMessages.push(currentField.dataset.gifthuntError);
		} else if (sessionType == "default") {
			gifthuntSessionData[currentFieldId] = currentField.value;
			valueCheck(currentField);
		}
	});

	// check popup design
	const popupDesign = document.querySelector("input[name='popup_design']:checked");
	if (!popupDesign && sessionType == "default") {
		errorCount++;
		togglePopupDesignError("display");
		errorMessages.push(document.querySelector("#popup_design_error-message").textContent);
	} else {
		togglePopupDesignError("hide");
		gifthuntSessionData["popup_design"] = popupDesign.value;
	}

	// check visibility values
	const active = document.querySelector("#active");
	gifthuntSessionData["active"] = active.checked ? 1 : 0;

	if (gifthuntSessionData["active"]) {
		// check dates
		const visibleFrom = document.querySelector("#visible_from");
		const visibleTo = document.querySelector("#visible_to");

		valueCheck(visibleFrom);
		valueCheck(visibleTo);

		if (visibleFrom.value) {
			gifthuntSessionData["visible_from"] = visibleFrom.value;
		} else {
			errorCount++;
			errorMessages.push("Session start date is required");
		}

		if (visibleTo.value) {
			gifthuntSessionData["visible_to"] = visibleTo.value;
		} else {
			errorCount++;
			errorMessages.push("Session end date is required");
		}
	}

	// display validation result
	if (errorCount) {
		const finalErrorMessage = errorMessages.map((errorMessage) => `<li> ${errorMessage}</li>`).join("");

		document.querySelectorAll(".gifthunt-form-result--error").forEach((errorSection) => {
			errorSection.querySelector(".gifthunt-form-result__content").innerHTML = `<ul>${finalErrorMessage}</ul>`;
			errorSection.classList.remove("hidden");
		});

		document.querySelectorAll(".gifthunt-form-result--success").forEach((successSection) => {
			successSection.querySelector(".gifthunt-form-result__content").innerHTML = "";
			successSection.classList.add("hidden");
		});

		document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");

		return;
	} else {
		document.querySelectorAll(".gifthunt-form-result").forEach((formResultSection) => {
			formResultSection.classList.add("hidden");
		});
		document.querySelector(".gifthunt-form-result__modal").classList.add("hidden");
	}

	// display loading animation and disable button
	const gifthuntSaveButtons = document.querySelectorAll(".gifthunt-save-session");
	gifthuntSaveButtons.forEach((saveButton) => {
		saveButton.querySelector("i").classList.remove("hidden");
		saveButton.disabled = true;
	});

	let gifthuntAction = "create";
	if (giftSessionId) {
		gifthuntAction = "update";
	}

	gifthuntSessionData["id"] = giftSessionId;

	// save or update
	const data = {
		nonce: gifthuntNonce,
		action: "gifthuntfree_action",
		gifthuntAction,
		gifthuntSessionData,
	};

	jQuery.post(ajaxurl, data, (response) => {
		if (response.status != "success") {
			document.querySelectorAll(".gifthunt-form-result--error").forEach((errorSection) => {
				errorSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
				errorSection.classList.remove("hidden");
			});

			document.querySelectorAll(".gifthunt-form-result--success").forEach((successSection) => {
				successSection.querySelector(".gifthunt-form-result__content").innerHTML = "";
				successSection.classList.add("hidden");
			});
			document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
		} else {
			if (gifthuntAction == "create") {
				// redirect to session page
				window.location.href = `admin.php?page=gifthuntfree-sessions&p=session-edit&id=${response.data}&msg=created`;
				return;
			} else {
				// display session updated message
				document.querySelectorAll(".gifthunt-form-result--success").forEach((successSection) => {
					successSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
					successSection.classList.remove("hidden");
				});

				// hide all previously displayed error messages, if there were any
				document.querySelectorAll(".gifthunt-form-result--error").forEach((errorSection) => {
					errorSection.querySelector(".gifthunt-form-result__content").innerHTML = "";
					errorSection.classList.add("hidden");
				});

				document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");

				// update gifts lists with db IDs
				const newGiftsList = response.gifts.map((gift) => {
					let currentGift = gift.gift.replace(/"/g, "&quot;");
					let giftFieldTemplate = document
						.querySelector("#gift-field-template")
						.textContent.replace(/%GIFTID%/gi, gift.id)
						.replace(/%GIFTDBID%/gi, gift.id)
						.replace(/%GIFTGIFT%/gi, currentGift);

					return giftFieldTemplate;
				});
				document.querySelector("#gifthunt-card__section--gift-list").innerHTML = newGiftsList.join("");
			}
		}

		gifthuntSaveButtons.forEach((saveButton) => {
			saveButton.querySelector("i").classList.add("hidden");
			saveButton.disabled = false;
		});
	});
}

/**
 * Handle form submit
 */
document.querySelectorAll(".gifthunt-save-session").forEach((saveButton) => {
	saveButton.addEventListener("click", (e) => {
		e.preventDefault();
		checkReadyToSave();
	});
});

document.querySelectorAll(".gifthunt-input-field").forEach((gifthuntInputField) => {
	gifthuntInputField.addEventListener("blur", (e) => {
		if (e.target.value && e.target.classList.contains("gifthunt-field-error")) {
			e.target.classList.remove("gifthunt-field-error");
			e.target.parentNode.querySelector(".gifthunt-error-message").classList.add("hidden");
		}
	});
});

/**
 * Gift fields check on load
 */
document.addEventListener("DOMContentLoaded", () => {
	const giftType = document.querySelector("input[name='gift_type']:checked");
	if (giftType && giftType.value == "oneMany") {
		// one gift for everyone
		const gift = document.querySelector("#gift");
		if (!gift.value) {
			valueCheck(gift);
		} else {
			valueCheck(gift);
		}
	} else {
		// multiple gifts
		let giftItems = [];
		let giftItemsCount = 0;
		const giftsError = document.querySelector("#gifts-error-message");

		document.querySelectorAll(".gift-item").forEach((giftItem) => {
			const currentGiftItem = giftItem.querySelector("input");
			if (currentGiftItem.value) {
				giftItemsCount++;
			}
		});

		if (!giftItemsCount) {
			giftsError.classList.remove("hidden");
		} else {
			giftsError.classList.add("hidden");
		}
	}
});

/**
 * Close modal
 */
document.querySelectorAll(".gifthunt-form-result__close-button").forEach((closeButton) => {
	closeButton.addEventListener("click", () => {
		document.querySelectorAll(".gifthunt-form-result__modal").forEach((gifthuntModal) => {
			gifthuntModal.classList.add("hidden");
		});
	});
});

/**
 * Delete session
 */
if (giftSessionId > 0) {
	document.querySelector("#button--session-delete").addEventListener("click", () => {
		document.querySelector(".gifthunt-form__delete-confirm-modal").classList.remove("hidden");
	});
}

// cancel delete
document.querySelector("#button--cancel-delete").addEventListener("click", () => {
	document.querySelector(".gifthunt-form__delete-confirm-modal").classList.add("hidden");
});

document.querySelector(".gifthunt-form__delete-confirm-modal__close-button").addEventListener("click", () => {
	document.querySelector(".gifthunt-form__delete-confirm-modal").classList.add("hidden");
});

// confirm delete
document.querySelector("#button--confirm-delete").addEventListener("click", (event) => {
	event.target.querySelector("i").classList.remove("hidden");
	event.target.disabled = true;

	let gifthuntAction = "delete";
	const data = {
		nonce: gifthuntNonce,
		action: "gifthuntfree_action",
		gifthuntAction,
		id: giftSessionId,
	};

	jQuery.post(ajaxurl, data, (response) => {
		event.target.querySelector("i").classList.add("hidden");
		event.target.disabled = false;
		document.querySelector(".gifthunt-form__delete-confirm-modal").classList.add("hidden");

		if (response.status != "success") {
			document.querySelectorAll(".gifthunt-form-result--error").forEach((errorSection) => {
				errorSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
				errorSection.classList.remove("hidden");
			});
			document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
		} else {
			// redirect to session list
			window.location.href = `admin.php?page=gifthuntfree-sessions&msg=deleted`;
			return;
		}
	});
});

/**
 * Send success email preview
 */
document.querySelector("#gifthunt-send-test-success-email").addEventListener("click", () => {
	document.querySelector(".gifthunt-form__test-mail-modal").classList.remove("hidden");
});

document.querySelector("#button--cancel-send-testmail").addEventListener("click", () => {
	document.querySelector(".gifthunt-form__test-mail-modal").classList.add("hidden");
});

document.querySelector(".gifthunt-form__test-mail-modal__close-button").addEventListener("click", () => {
	document.querySelector(".gifthunt-form__test-mail-modal").classList.add("hidden");
});

document.querySelector("#button--confirm-send-testmail").addEventListener("click", (event) => {
	/**
	 * Check email format
	 */
	const toMail = document.querySelector("#to").value;
	if (!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(toMail)) {
		document.querySelector("#to").focus();
		alert("Please, use a valid email address");
		return;
	}

	event.target.disabled = true;
	event.target.querySelector("i").classList.remove("hidden");

	let gifthuntAction = "send_test_mail";

	let successEmailBodyContent = "";
	if (isVisible(document.getElementById("success_email_body"))) {
		successEmailBodyContent = document.getElementById("success_email_body").value;
	} else {
		successEmailBodyContent = tinyMCE.editors["success_email_body"].getContent();
	}

	const data = {
		nonce: gifthuntNonce,
		action: "gifthuntfree_action",
		gifthuntAction,
		to: toMail,
		success_email_sender_name: document.querySelector("#success_email_sender_name").value,
		success_email_sender_email: document.querySelector("#success_email_sender_email").value,
		success_email_subject: document.querySelector("#success_email_subject").value,
		success_email_body: successEmailBodyContent,
	};

	jQuery.post(ajaxurl, data, (response) => {
		event.target.querySelector("i").classList.add("hidden");
		event.target.disabled = false;
		document.querySelector(".gifthunt-form__test-mail-modal").classList.add("hidden");

		if (response.status != "success") {
			document.querySelectorAll(".gifthunt-form-result--error").forEach((errorSection) => {
				errorSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
				errorSection.classList.remove("hidden");
			});

			// hide previously display success message, if there were any
			document.querySelectorAll(".gifthunt-form-result--success").forEach((successSection) => {
				successSection.querySelector(".gifthunt-form-result__content").innerHTML = "";
				successSection.classList.add("hidden");
			});

			document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
		} else {
			// show success message
			document.querySelectorAll(".gifthunt-form-result--success").forEach((successSection) => {
				successSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
				successSection.classList.remove("hidden");
			});

			// hide all previously displayed error messages, if there were any
			document.querySelectorAll(".gifthunt-form-result--error").forEach((errorSection) => {
				errorSection.querySelector(".gifthunt-form-result__content").innerHTML = "";
				errorSection.classList.add("hidden");
			});

			document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
		}
	});
});

/**
 * Accordion
 */
const accordionTitle = document.querySelectorAll(".gifthunt-accordion__title");
if (accordionTitle.length) {
	accordionTitle.forEach((title) => {
		title.addEventListener("click", () => toggleAccordion(title));
	});
}

function toggleAccordion(title) {
	const accordionSection = title.parentNode;
	const accordion = accordionSection.parentNode;

	if (accordionSection.classList.contains("gifthunt-accordion__section--active")) {
		// this is the active section, don't do anything
		return;
	}

	const activeAccordionSection = accordion.querySelector(".gifthunt-accordion__section--active");
	if (activeAccordionSection) {
		activeAccordionSection.classList.remove("gifthunt-accordion__section--active");
	}

	accordionSection.classList.add("gifthunt-accordion__section--active");

	return;
}

const gifthuntCustomIconUploadButton = document.querySelector("#gifthunt__icon-upload-button");
if (gifthuntCustomIconUploadButton) {
	let gifthuntCustomIconUploader;

	gifthuntCustomIconUploadButton.addEventListener("click", (e) => {
		e.preventDefault();

		gifthuntCustomIconUploader
			? gifthuntCustomIconUploader.open()
			: ((gifthuntCustomIconUploader = wp.media.frames.gifthuntCustomIconUploader =
					wp.media({
						title: "Select custom icon",
						button: {
							text: "Use this custom icon",
						},
						multiple: !1,
					})).on("select", function () {
					attachment = gifthuntCustomIconUploader.state().get("selection").first().toJSON();
					document.querySelector("#custom_icon").value = attachment.url;
					document.querySelector("#gifthunt__icon-upload-preview img").src = attachment.url;
					document.querySelector("#gifthunt__icon-upload-preview label").style.display = "block";
					document.querySelector("#gifthunt__icon-upload-preview input[type='radio']").checked = true;
			  }),
			  gifthuntCustomIconUploader.open());
	});
}

// remove success parameter from URL (to avoid display the success message after refresh)
let currentLocation = window.top.location.href;
if (currentLocation.includes("msg=created")) {
	window.history.pushState("gifthuntcreated", "", currentLocation.replace("msg=created", ""));
}

// icon animation preview
const iconAnimationPreviewItems = document.querySelectorAll("#gifthunt__icon-animation-list label");
if (iconAnimationPreviewItems) {
	iconAnimationPreviewItems.forEach((iconAnimationPreview) => {
		iconAnimationPreview.addEventListener("mouseover", () => {
			toggleAnimationPreview("mouseover", iconAnimationPreview);
		});

		iconAnimationPreview.addEventListener("mouseleave", () => {
			toggleAnimationPreview("mouseleave", iconAnimationPreview);
		});
	});
}

function toggleAnimationPreview(event, iconAnimationItem) {
	const animationPreviewSpan = iconAnimationItem.querySelector(".gifthunt-icon-animation-preview");
	const animationClass = animationPreviewSpan.dataset.animation;
	if (event == "mouseover") {
		animationPreviewSpan.querySelector("span").classList.add(animationClass);
	} else {
		animationPreviewSpan.querySelector("span").classList.remove(animationClass);
	}
}

// popup design preview
const popupDesignPreviewItems = document.querySelectorAll(".gifthunt-popup-design-preview");
const popupPreviewModal = document.querySelector(".gifthunt-form__popup-design-preview-modal");
if (popupDesignPreviewItems && popupPreviewModal) {
	popupDesignPreviewItems.forEach((popupDesignPreviewItem) => {
		popupDesignPreviewItem.addEventListener("click", () => {
			togglePopupDesignPreview(popupDesignPreviewItem, "display");
		});
	});

	popupPreviewModal.addEventListener("click", () => {
		togglePopupDesignPreview(this, "hide");
	});
}

function togglePopupDesignPreview(previewItem, action) {
	if (action == "display") {
		const previewImage = previewItem.querySelector("img");
		popupPreviewModal.innerHTML = previewImage.outerHTML;
		popupPreviewModal.classList.remove("hidden");
	} else {
		popupPreviewModal.removeChild(popupPreviewModal.firstChild);
		popupPreviewModal.classList.add("hidden");
	}
}

// gifthunt session from accordion
const gifthuntFormAccordionLabels = document.querySelectorAll(".gifthunt-form-group__label--accordion");
if (gifthuntFormAccordionLabels) {
	gifthuntFormAccordionLabels.forEach((gifthuntFormAccordionLabel) => {
		gifthuntFormAccordionLabel.addEventListener("click", toggleFormSectionBody);
	});
}

function toggleFormSectionBody() {
	const section = this.closest(".gifthunt-card__section");
	if (section.classList.contains("gifthunt-card__section--closed")) {
		section.classList.remove("gifthunt-card__section--closed");
		section.classList.add("gifthunt-card__section--opened");
	} else {
		section.classList.remove("gifthunt-card__section--opened");
		section.classList.add("gifthunt-card__section--closed");
	}
}

// success email template preview
const successEmailPreviewItems = document.querySelectorAll("#gifthunt__success-email-template-list label");
const successEmailPreviewModal = document.querySelector(".gifthunt-form__success-email-template-preview-modal");
const successEmailPreviewModalBody = document.querySelector(".gifthunt-form__success-email-template-preview-modal .gifthunt-form__success-email-template-preview-modal-body");
const successEmailPreviewModalCloseButton = document.querySelector(".gifthunt-form__success-email-template-preview-modal__close-button");
const successEmailPreviewModalCancelButton = document.querySelector("#button--cancel-success-email-template-preview");
const successEmailPreviewModalSelectTemplateButton = document.querySelector("#button--confirm-success-email-template-preview");
let successEmailTemplate = "";

if (successEmailPreviewItems && successEmailPreviewModal) {
	successEmailPreviewItems.forEach((successEmailPreviewItem) => {
		successEmailPreviewItem.addEventListener("click", () => {
			toggleSuccessEmailPreview(successEmailPreviewItem, "display");
		});
	});

	successEmailPreviewModalCloseButton.addEventListener("click", () => {
		toggleSuccessEmailPreview(null, "hide");
	});

	successEmailPreviewModalCancelButton.addEventListener("click", () => {
		toggleSuccessEmailPreview(null, "hide");
	});

	successEmailPreviewModalSelectTemplateButton.addEventListener("click", successEmailUseSelectedTemplate);
}

function successEmailUseSelectedTemplate() {
	const selectedTemplateHTML = document.querySelector(`#gifthunt-success-email-template-${successEmailTemplate}`).outerHTML;
	if (isVisible(document.getElementById("success_email_body"))) {
		document.getElementById("success_email_body").value = selectedTemplateHTML;
	} else {
		tinyMCE.editors["success_email_body"].setContent(selectedTemplateHTML);
	}
	toggleSuccessEmailPreview(null, "hide");
	document.querySelector("#wp-success_email_body-wrap").scrollIntoView();
}

function toggleSuccessEmailPreview(previewItem, action) {
	if (action == "display") {
		successEmailTemplate = previewItem.dataset.template;
		const previewImage = previewItem.querySelector("img");
		successEmailPreviewModalBody.innerHTML = previewImage.outerHTML;
		successEmailPreviewModal.classList.remove("hidden");
	} else {
		successEmailTemplate = "";
		successEmailPreviewModalBody.removeChild(successEmailPreviewModalBody.firstChild);
		successEmailPreviewModal.classList.add("hidden");
	}
}

/**
 * Shortcode placement
 */
const shortcodePlacementInput = document.getElementById("gifthunt-placement-shortcode");
shortcodePlacementInput.addEventListener("focus", (e) => {
	e.target.select();
});

/**
 * Display shortcode info if shortcode placement is selected
 */
const shortcodePlacementInfo = document.getElementById("gifthunt-placement-shortcode-info");
const toggleIconShortcodeSection = (e) => {
	const iconAnimationSection = document.getElementById("icon_animation_section");
	const displayTypeSection = document.getElementById("display_type_section");
	const displayTypeTimeSection = document.getElementById("time_to_display_section");
	const displayTypePageviewSection = document.getElementById("pageview_to_display_section");

	if (e.target.value == "shortcode") {
		/**
		 * Display shortcode info section
		 */
		shortcodePlacementInfo.classList.remove("hidden");
		/**
		 * Hide display type and icon animation sections
		 */
		iconAnimationSection.classList.add("hidden");
		displayTypeSection.classList.add("hidden");
		displayTypeTimeSection.classList.add("hidden");
		displayTypePageviewSection.classList.add("hidden");
	} else {
		/**
		 * Hide shortcode info section
		 */
		shortcodePlacementInfo.classList.add("hidden");
		/**
		 * Show display type and icon animation sections
		 */
		iconAnimationSection.classList.remove("hidden");
		displayTypeSection.classList.remove("hidden");
		if (document.querySelector(`input[name="display_type"]:checked`).value == "time") {
			displayTypeTimeSection.classList.remove("hidden");
		} else {
			displayTypePageviewSection.classList.remove("hidden");
		}
	}
};

const iconPlacementOptions = document.querySelectorAll("input[name=icon_placement]");
iconPlacementOptions.forEach((placementOption) => {
	placementOption.addEventListener("change", toggleIconShortcodeSection);
});

/**
 * Shortcode preview alert window
 */

const shortcodePreviewAlertModal = document.querySelector(".gifthunt-form__shortcode-preview-alert-modal");
const shortcodePreviewAlertCloseButton = document.querySelector(".gifthunt-form__shortcode-preview-alert-modal__close-button");
const shortcodePreviewAlertPrimaryButton = document.getElementById("shortcode-preview-alert-close");
const sessionPreviewButton = document.getElementById("gifthunt-session-preview-button");

shortcodePreviewAlertCloseButton.addEventListener("click", toggleShortcodePreviewAlert);
if (sessionPreviewButton) {
	sessionPreviewButton.addEventListener("click", (e) => {
		e.preventDefault();
		const previewURL = e.target.href;
		const iconPlacementSelectedOption = document.querySelector(`input[name="icon_placement"]:checked`);

		/**
		 * Check session settings for shortcode preview
		 */
		const sessionShortcodePreviewStatus = document.getElementById("shortcode-preview-current-status");
		const sessionShortcodePreviewDateSettings = document.getElementById("shortcode-preview-date-settings");

		if (document.querySelector(`input[name="active"]`).checked) {
			sessionShortcodePreviewStatus.innerHTML = "<em>The session is active</em> ✅";
		} else {
			sessionShortcodePreviewStatus.innerHTML = "<em>You can't preview your session when it's inactive</em> ❌";
		}

		const shortcodePreviewDateTemp = Date.now();
		const shortcodePreviewStartDate = Date.parse(document.getElementById("visible_from").value);
		const shortcodePreviewEndDate = Date.parse(document.getElementById("visible_to").value);

		if (shortcodePreviewDateTemp < shortcodePreviewStartDate || shortcodePreviewDateTemp > shortcodePreviewEndDate) {
			sessionShortcodePreviewDateSettings.innerHTML = "<em>You can't preview your session with these start and end dates</em> ❌";
		} else {
			sessionShortcodePreviewDateSettings.innerHTML = "<em>The session start or end dates are correct</em> ✅";
		}

		if (iconPlacementSelectedOption.value == "shortcode") {
			// display shortcode preview alert
			toggleShortcodePreviewAlert();
		} else {
			window.open(previewURL);
		}
	});
}

shortcodePreviewAlertPrimaryButton.addEventListener("click", toggleShortcodePreviewAlert);

function toggleShortcodePreviewAlert() {
	if (shortcodePreviewAlertModal.classList.contains("hidden")) {
		// show modal
		shortcodePreviewAlertModal.classList.remove("hidden");
	} else {
		// hide modal
		shortcodePreviewAlertModal.classList.add("hidden");
	}
}

/**
 * Toggle default session type settings
 */

const sessionTypeDefaultSettingBlocks = document.querySelectorAll(".gifthunt-card--session-type-default-setting");
const sessionTypeCustomPopupSettingBlocks = document.querySelectorAll(".gifthunt-card--session-type-custom-popup-setting");

const toggleSessionTypeSettings = (sessionType) => {
	switch (sessionType) {
		case "default": {
			/**
			 * Show default session type blocks
			 */
			const selectedGiftType = document.querySelector(`input[name="gift_type"]:checked`);
			sessionTypeDefaultSettingBlocks.forEach((block) => {
				/**
				 * Check gift type to display one gift or multiple gifts block
				 */
				if (block.id == "gifthunt-card--one-gift") {
					if (selectedGiftType.value == "oneMany") {
						/**
						 * Display One gift block
						 */
						block.classList.remove("hidden");
					}
				} else if (block.id == "gifthunt-card--more-gifts") {
					if (selectedGiftType.value != "oneMany") {
						/**
						 * Display More gifts block
						 */
						block.classList.remove("hidden");
					}
				} else {
					block.classList.remove("hidden");
				}
			});

			/**
			 * Hide custom popup session type blocks
			 */
			sessionTypeCustomPopupSettingBlocks.forEach((block) => {
				block.classList.add("hidden");
			});

			break;
		}
		case "custom_popup": {
			/**
			 * Show custom popup session type blocks
			 */
			sessionTypeCustomPopupSettingBlocks.forEach((block) => {
				block.classList.remove("hidden");
			});

			/**
			 * Hide default session type blocks
			 */
			sessionTypeDefaultSettingBlocks.forEach((block) => {
				block.classList.add("hidden");
			});

			break;
		}
		default: {
			break;
		}
	}
};

const sessionTypes = document.querySelectorAll(`input[name="session_type"]`);
if (sessionTypes) {
	sessionTypes.forEach((sessionType) => {
		sessionType.addEventListener("change", (e) => {
			toggleSessionTypeSettings(e.target.value);
		});
	});
}
