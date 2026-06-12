<footer class="main-footer">
    <div class="container">
        <div class="row gy-4">

            <!-- Brand -->
            <div class="col-lg-3 col-md-6">
                <h3 class="footer-logo">Key<span>Stone</span></h3>
                <p class="footer-text">
                    Premium event management and instant ticket booking platform. 
                    Discover amazing events happening around you.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Quick Links</h5>

                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Events</a></li>
                    <li><a href="#">My Bookings</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>

            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Contact</h5>

                <p class="footer-contact">
                    <i class="fa fa-envelope"></i>
                    support@keystone.com
                </p>

                <p class="footer-contact">
                    <i class="fa fa-phone"></i>
                    +91 XXXXX XXXXX
                </p>

                <p class="footer-contact">
                    <i class="fa fa-location-dot"></i>
                    Kolkata, India
                </p>

            </div>

            <!-- Social -->
            <div class="col-lg-3 col-md-6 text-lg-end">

                <h5 class="footer-title">Follow Us</h5>

                <div class="footer-socials">

                    <a href="#"><i class="fab fa-facebook-f"></i></a>

                    <a href="#"><i class="fab fa-instagram"></i></a>

                    <a href="#"><i class="fab fa-twitter"></i></a>

                    <a href="#"><i class="fab fa-linkedin-in"></i></a>

                </div>

            </div>

        </div>

        <!-- Bottom -->
        <div class="footer-divider"></div>

        <div class="row pt-3">

            <div class="col-md-6 text-center text-md-start">

                <p class="copyright">
                    © <?php echo date("Y"); ?> KeyStone. All rights reserved.
                </p>

            </div>

            <div class="col-md-6 text-center text-md-end">

                <p class="developer">
                    Developed by <span>Jit Biswas</span>
                </p>

            </div>

        </div>

    </div>
</footer>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

.main-footer{
background:#070617;
padding:70px 0 25px;
margin-top:120px;
border-top:1px solid rgba(255,59,122,0.15);
}

.footer-logo{
color:white;
font-weight:800;
}

.footer-logo span{
color:#ff3b7a;
}

.footer-text{
color:#9ca3af;
font-size:14px;
line-height:1.7;
margin-top:15px;
}

.footer-title{
color:white;
margin-bottom:20px;
font-size:18px;
}

.footer-links{
list-style:none;
padding:0;
}

.footer-links li{
margin-bottom:10px;
}

.footer-links a{
color:#9ca3af;
text-decoration:none;
transition:.3s;
font-size:14px;
}

.footer-links a:hover{
color:#ff3b7a;
padding-left:5px;
}

.footer-contact{
color:#9ca3af;
font-size:14px;
margin-bottom:10px;
}

.footer-contact i{
color:#ff3b7a;
margin-right:8px;
}

.footer-socials a{

display:inline-flex;
align-items:center;
justify-content:center;

width:42px;
height:42px;

background:rgba(255,255,255,.05);

border-radius:50%;

color:white;

margin-left:10px;

transition:.4s;

border:1px solid rgba(255,255,255,.08);

}

.footer-socials a:hover{

background:#ff3b7a;

transform:translateY(-6px);

box-shadow:0 10px 25px rgba(255,59,122,.4);

}

.footer-divider{

height:1px;

background:linear-gradient(
to right,
transparent,
rgba(255,59,122,.3),
transparent
);

margin-top:50px;

}

.copyright{

color:#9ca3af;

font-size:14px;

}

.developer{

color:#9ca3af;

font-size:14px;

}

.developer span{

color:#ff3b7a;

font-weight:600;

}

</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>