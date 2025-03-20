// Script to remove investigational use messages
(function() {
  function removeInvestigationalMessages() {
    // Remove banner with fixed position at bottom
    const fixedElements = document.querySelectorAll('[class*="fixed bottom-2 z-50"]');
    fixedElements.forEach(el => el.style.display = 'none');
    
    // Remove text in headers
    const textElements = document.querySelectorAll('.text-common-light.mr-3.text-lg');
    textElements.forEach(el => el.style.display = 'none');
    
    // Remove any elements containing the text
    document.querySelectorAll('*').forEach(el => {
      if (el.innerText && 
          (el.innerText.includes('INVESTIGATIONAL USE ONLY') || 
           el.innerText.includes('for investigational use only'))) {
        el.style.display = 'none';
      }
    });
  }
  
  // Run on page load
  removeInvestigationalMessages();
  
  // Run periodically to catch any dynamically added elements
  setInterval(removeInvestigationalMessages, 1000);
})(); 