<footer>
    <div class="footerwrap">
        <div class="footerleft">
            <div class="footerlogo"></div>
            <div class="footercopy">&copy; <?php echo date('Y');?>, ad3media.com</div>
        </div>
        <div class="footerright">
            <ul>
                <li>FEATURES</li>
                <a href="/features"><li>MONETIZATION</li></a>
                <a href="/features"><li>WP PLUGIN</li></a>
                <a href="/features"><li>DEVELOPER API</li></a>
            </ul>
            <ul>
                <li>ADVERTISERS</li>
                <a href="/contact"><li>LEARN MORE</li></a>
            </ul>
            <ul>
                <li>PUBLISHERS</li>
                <a href="/#betasignup"><li>SIGN UP</li></a>
            </ul>
            <ul>
                <li>COMPANY</li>
                <a href="/contact"><li>ABOUT</li></a>
            </ul>
            <ul>
                <li>CONTACT</li>
                <a href="/contact"><li>SUPPORT</li></a>
            </ul>
        </div>
    </div>
</footer>

</body>

<!-- GOOGLE ANALYTICS -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-28821011-14', 'auto');
  ga('send', 'pageview');

</script>
<!-- END ANALYTICS -->

<script>
    jQuery(document).ready(function(){
        // SHOW/HIDE NAV OVERLAY
        $('.navmore').click(function() {
            $('.mainnav-overlay').show();
            $(this).css('display', 'none');
            $('.closenavmore').css('display', 'block');
            $('body').css('overflow', 'hidden');
        });
        $('.closenavmore').click(function() {
            $('.mainnav-overlay').hide();
            $(this).css('display', 'none');
            $('.navmore').css('display', 'block');
            $('body').css('overflow', 'inherit');
        });
    });
</script>

</html>