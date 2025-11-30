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

if(huntersCount > 0){
  /**
   * Display delete hunter confirm modal
   */

  let selectedHunterId = 0;
  document.querySelectorAll(".button-delete-hunter").forEach(deleteHunterButton => {
    deleteHunterButton.addEventListener("click", (e) => showDeleteHunterConfirmModal(e));
  });
  
  function showDeleteHunterConfirmModal(event){
    selectedHunterId = event.target.dataset.hunterId;
  
    // get selected hunters data and add it to the modal
    document.querySelector("#hunter-first-name").textContent = event.target.dataset.hunterFirstName;
    document.querySelector("#hunter-last-name").textContent = event.target.dataset.hunterLastName;
    document.querySelector("#hunter-email").textContent = event.target.dataset.hunterEmail;
  
    document.querySelectorAll(".hunter-collected-gift").forEach(hunterCollectedGift => {
      hunterCollectedGift.textContent = event.target.dataset.hunterGift;
    });
  
    // display modal
    document.querySelector(".gifthunt-form__delete-hunter-confirm-modal").classList.remove("hidden");
  }
  
  document.querySelector(".gifthunt-form__delete-hunter-confirm-modal__close-button").addEventListener("click", () => {
    document.querySelector(".gifthunt-form__delete-hunter-confirm-modal").classList.add("hidden");
  });
  
  document.querySelector("#button--cancel-delete-hunter").addEventListener("click", () => {
    document.querySelector(".gifthunt-form__delete-hunter-confirm-modal").classList.add("hidden");
  });
  
  // confirm delete selected hunter
  document.querySelector("#button--confirm-delete-hunter").addEventListener("click", (event) => {
    event.target.disabled = true;
    event.target.querySelector("i").classList.remove("hidden");
  
    let gifthuntAction = "delete_hunter";
    const data = {
      'nonce': gifthuntfreeNonce,
      'action': 'gifthuntfree_action',
      gifthuntAction,
      "id": selectedHunterId
    };
  
    jQuery.post(ajaxurl, data, (response) => {
      event.target.querySelector("i").classList.add("hidden");
      event.target.disabled = false;
      document.querySelector(".gifthunt-form__delete-hunter-confirm-modal").classList.add("hidden");
  
      if(response.status != "success"){
        document.querySelectorAll(".gifthunt-form-result--error").forEach(errorSection => {
          errorSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
          errorSection.classList.remove("hidden");
        });
        document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
      } else {
        document.querySelectorAll(".gifthunt-form-result--success").forEach(successSection => {
          successSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
          successSection.classList.remove("hidden");
        });
        document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
  
        // remove selected hunter from the list
        document.querySelector(`#hunter-${selectedHunterId}`).remove();
  
        // change hunter counter
        const hunterCount = document.querySelector(".hunter-count");
        if(hunterCount){
          const currentHunterCount = hunterCount.dataset.count - 1;
          hunterCount.textContent = currentHunterCount;

          if(currentHunterCount < 1){
            window.location.reload();
          }
        }
        selectedHunterId = 0;
      }
    });
  
  });
  
  /**
   * Delete all hunters
   */
  document.querySelector("#button-delete-all-hunters").addEventListener("click", () => {
    document.querySelector(".gifthunt-form__delete-confirm-modal").classList.remove("hidden");
  });
  
  document.querySelector(".gifthunt-form__delete-confirm-modal__close-button").addEventListener("click", () => {
    document.querySelector(".gifthunt-form__delete-confirm-modal").classList.add("hidden");
  });
  
  document.querySelector("#button--cancel-delete").addEventListener("click", () => {
    document.querySelector(".gifthunt-form__delete-confirm-modal").classList.add("hidden");
  });
  
  document.querySelector("#button--confirm-delete").addEventListener("click", (event) => {
    event.target.querySelector("i").classList.remove("hidden");
    event.target.disabled = true;
  
    let gifthuntAction = "delete_all_hunters";
    const data = {
      'nonce': gifthuntfreeNonce,
      'action': 'gifthuntfree_action',
      gifthuntAction,
      "id": giftSessionId
    };
  
    jQuery.post(ajaxurl, data, (response) => {
      event.target.querySelector("i").classList.add("hidden");
      event.target.disabled = false;
      document.querySelector(".gifthunt-form__delete-confirm-modal").classList.add("hidden");
  
      if(response.status != "success"){
        document.querySelectorAll(".gifthunt-form-result--error").forEach(errorSection => {
          errorSection.querySelector(".gifthunt-form-result__content").innerHTML = response.data;
          errorSection.classList.remove("hidden");
        });
        document.querySelector(".gifthunt-form-result__modal").classList.remove("hidden");
      } else {
        const currentLocation = window.location.href;
        window.location.href = `${currentLocation}&msg=deleted`;
      }
    });
  });
}