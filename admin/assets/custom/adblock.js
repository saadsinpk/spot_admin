
    function adBlockDetected(detected){
        
        if(detected){
            
            document.getElementById("adblock_layout").style.display = "flex";
            
        } else {
            
            $('#adblock_layout').hide();
        }
        
    }
    
    async function detectAdBlock() {
        let adBlockEnabled = false
        
        const googleAdUrl = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'
        
        try {
            
            await fetch(new Request(googleAdUrl)).catch(_ => adBlockEnabled = true)
            
        } catch (e) {
            
            adBlockEnabled = true
            
        } finally {
            
            adBlockDetected(adBlockEnabled);
            
        }
        
    }
    
    detectAdBlock()