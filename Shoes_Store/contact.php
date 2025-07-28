<?php include('partials_front/menu.php'); ?>

<section class="product-search">
    <div class="container">
        <h2 class="text-center text-white">Contact Me</h2>
        
        <div style="text-align: left; margin-bottom: 30px;">
            <p class="text-white" style="margin-bottom: 10px; font-size: 1.1rem;">
                <i class="fas fa-paper-plane" style="margin-right: 10px;"></i>djfsdhfsds@gmail.com
            </p>
            <p class="text-white" style="margin-bottom: 20px; font-size: 1.1rem;">
                <i class="fas fa-phone-square-alt" style="margin-right: 10px;"></i>+62 223343454534354
            </p>
        </div>
        
        <div class="social-icons" style="flex-direction: row; justify-content: flex-start; margin-bottom: 40px; padding-top: 0;">
            <a href="#" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                text-decoration: none;
                margin-right: 15px;
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            ">
                <i class="fab fa-facebook"></i>
            </a>
            <a href="#" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                text-decoration: none;
                margin-right: 15px;
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            ">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                text-decoration: none;
                margin-right: 15px;
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            ">
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="" style="
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                text-decoration: none;
                transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            ">
                <i class="fab fa-twitter"></i>
            </a>
        </div>
        <div style="display: flex; justify-content: center; align-items: center; height: 50vh;">
            <form name="submit-to-google-sheet" style="max-width: 70%; margin-top: 30px;">
                <input type="text" name="Name" placeholder="Your Name" required style="
                    width: 100%;
                    padding: 18px 20px;
                    margin-bottom: 20px;
                    border: 2px solid rgba(255,255,255,0.2);
                    border-radius: 12px;
                    background: rgba(255,255,255,0.95);
                    color: #333;
                    font-size: 1rem;
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    box-sizing: border-box;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                " />
                
                <input type="email" name="Email" placeholder="Your Email" required style="
                    width: 100%;
                    padding: 18px 20px;
                    margin-bottom: 20px;
                    border: 2px solid rgba(255,255,255,0.2);
                    border-radius: 12px;
                    background: rgba(255,255,255,0.95);
                    color: #333;
                    font-size: 1rem;
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    box-sizing: border-box;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                " />
                
                <textarea name="Message" rows="6" placeholder="Your Message" style="
                    width: 100%;
                    padding: 18px 20px;
                    margin-bottom: 30px;
                    border: 2px solid rgba(255,255,255,0.2);
                    border-radius: 12px;
                    background: rgba(255,255,255,0.95);
                    color: #333;
                    font-size: 1rem;
                    resize: vertical;
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    min-height: 150px;
                    box-sizing: border-box;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                "></textarea>
                
                <button type="submit" class="btn btn-primary" style="
                    background: linear-gradient(135deg, var(--primary), var(--secondary));
                    color: white;
                    border: none;
                    padding: 15px 40px;
                    border-radius: 50px;
                    font-size: 1rem;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    box-shadow: 0 8px 20px rgba(255, 77, 103, 0.4);
                    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
                ">Submit</button>
            </form>
        </div>
        
        <span id="msg" style="color: white; display: block; margin-top: 20px; text-align: center; font-weight: 600;"></span>
    </div>
</section>

<script>
    const scriptURL = 'https://script.google.com/macros/s/AKfycbwgUyXs-NSHH-zj4rjx-HBun_YAFOhf1rCUlCSJaZBdOWS1udAUEBkFTJz4m7_XQMX63Q/exec'
    const form = document.forms['submit-to-google-sheet']
    const msg = document.getElementById("msg")

    form.addEventListener('submit', e => {
        e.preventDefault()
        fetch(scriptURL, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => {
                msg.innerHTML = "Message sent successfully"
                msg.style.color = "#2ECC71"
                setTimeout(function() {
                    msg.innerHTML = ""
                }, 5000)
                form.reset()
            })
            .catch(error => {
                console.error('Error!', error.message)
                msg.innerHTML = "Error sending message. Please try again."
                msg.style.color = "#FF4D67"
                setTimeout(function() {
                    msg.innerHTML = ""
                }, 5000)
            })
    })
    
    // Add hover effects to social icons
    document.querySelectorAll('.social-icons a').forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) rotate(10deg) translateZ(30px)';
            this.style.boxShadow = '0 15px 35px rgba(0, 0, 0, 0.3)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) rotate(0deg) translateZ(0px)';
            this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.2)';
        });
    });
    
    // Add focus effects to form inputs
    document.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--primary)';
            this.style.boxShadow = '0 0 0 3px rgba(255, 77, 103, 0.2), 0 8px 32px rgba(0,0,0,0.15)';
            this.style.transform = 'translateZ(10px)';
            this.style.background = 'rgba(255,255,255,1)';
        });
        
        input.addEventListener('blur', function() {
            this.style.borderColor = 'rgba(255,255,255,0.2)';
            this.style.boxShadow = '0 8px 32px rgba(0,0,0,0.1)';
            this.style.transform = 'translateZ(0px)';
            this.style.background = 'rgba(255,255,255,0.95)';
        });
    });
    
    // Add button hover effect
    document.querySelector('button[type="submit"]').addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px) translateZ(20px) rotateX(5deg)';
        this.style.boxShadow = '0 15px 35px rgba(255, 77, 103, 0.5)';
    });
    
    document.querySelector('button[type="submit"]').addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) translateZ(5px) rotateX(0deg)';
        this.style.boxShadow = '0 8px 20px rgba(255, 77, 103, 0.4)';
    });
</script>

<style>
    input::placeholder,
    textarea::placeholder {
        color: rgba(100, 100, 100, 0.7) !important;
    }
    
    input:focus::placeholder,
    textarea:focus::placeholder {
        color: rgba(100, 100, 100, 0.5) !important;
    }
</style>

<?php include('partials_front/footer.php'); ?>