@extends('home')

@section('website-title')
    <title>Mergersales | Contact Us</title>
@endsection

@section('website-content')
    <section class="hidden-section single-par2  " data-scrollax-parent="true">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem" data-bg="{{ asset('images/38.jpg') }}" data-scrollax="properties: { translateY: '30%' }">
            </div>
        </div>
        <div class="container">
            <div class="section-title center-align big-title">
                <h2><span>Get In Touch</span></h2>
                <h4>Have questions about buying or selling businesses confidentially? Our team is here to help you navigate
                    the M&A process.</h4>
            </div>
        </div>
    </section>
    <section class="gray-bg small-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="services-item fl-wrap">
                        <i class="fal fa-headset"></i>
                        <h4>Platform Support <span>01</span></h4>
                        <p>Get assistance with listings, inquiries, and platform features.</p>
                        <a href="support@mergersales.com" class="serv-link sl-b">support@mergersales.com</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="services-item fl-wrap">
                        <i class="fal fa-comments-alt"></i>
                        <h4>Business Inquiries<span>02</span></h4>
                        <p>Questions about listing your business or acquisition strategies.</p>
                        <a href="{{ route('webite-business') }}" class="serv-link sl-b">inquiries@mergersales.com</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="services-item fl-wrap">
                        <i class="fal fa-globe-americas"></i>
                        <h4>Global Platform <span>03</span></h4>
                        <p>Access our worldwide marketplace from anywhere.</p>
                        <a href="{{ route('webite-contact') }}" class="serv-link sl-b">Available 24/7 Worldwide</a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="contacts-opt fl-wrap">
                <div class="contact-social">
                    <span class="cs-title">Connect with us: </span>
                    <ul class="social-grid">
                        <li><a href="https://www.linkedin.com/company/merger-sales/" target="_blank"><i
                                    class="fab fa-linkedin-in"></i></a></li>
                        <li><a href="https://x.com/MergerSales" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://www.facebook.com/share/15xB6mdH4c/?mibextid=wwXIfr" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a></li>
                        <li><a href="https://www.instagram.com/mergersales" target="_blank"><i
                                    class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <style>
                .social-grid {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    /* 2 columns */
                    gap: 10px;
                    padding: 0;
                    list-style: none;
                }

                .social-grid li a {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 60px;
                    background: #CCAA57;
                    /* aapka theme color */
                    color: #fff;
                    font-size: 20px;
                    border-radius: 8px;
                    text-decoration: none;
                    transition: 0.3s;
                }

                .social-grid li a:hover {
                    background: #333;
                }
            </style>
        </div>
    </section>
@endsection
