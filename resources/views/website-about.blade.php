@extends('home')

@section('website-title')
    <title>Mergersales | About US</title>
@endsection

@section('website-content')
    <section class="hidden-section single-par2  " data-scrollax-parent="true">
        <div class="bg-wrap bg-parallax-wrap-gradien">
            <div class="bg par-elem " data-bg="{{ asset('images/35.jpg') }}" data-scrollax="properties: { translateY: '30%' }">
            </div>
        </div>
        <div class="container">
            <div class="section-title center-align big-title">
                <h2><span>About Mergersales</span></h2>
                <h4>Transforming how businesses are bought and sold worldwide through confidentiality and accessibility.
                </h4>
            </div>
        </div>
    </section>
    <section class="gray-bg small-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="services-item fl-wrap">
                        <i class="fal fa-user-secret"></i>
                        <h4>100% Confidential <span>01</span></h4>
                        <p>Businesses are listed anonymously. No company names, no exposure, only essential details to
                            maintain complete privacy throughout the process.</p>
                        <a href="#" class="serv-link">Learn More</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="services-item fl-wrap">
                        <i class="fal fa-hand-holding-usd"></i>
                        <h4>Completely Free <span>02</span></h4>
                        <p>No listing fees, no commissions, no subscriptions. Join, browse, and connect with buyers/sellers
                            without any cost barriers or hidden charges.</p>
                        <a href="#" class="serv-link">Learn More</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="services-item fl-wrap">
                        <i class="fal fa-globe-americas"></i>
                        <h4>Global Reach <span>03</span></h4>
                        <p>Access businesses from around the world across all industries. Connect with international buyers
                            and sellers in a seamless, borderless marketplace.</p>
                        <a href="#" class="serv-link">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="about-wrap">
                <div class="row">
                    <div class="col-md-5">
                        <div class="about-title fl-wrap">
                            <h2>Our <span>Mission</span></h2>
                            <h4>Democratizing M&A through transparency and accessibility.</h4>
                        </div>
                        <p>Mergersales was founded to solve the #1 problem in business transactions: confidentiality.
                            Traditional M&A processes expose businesses prematurely, risking employee morale, customer
                            relationships, and competitive advantage. We believe every business owner deserves to explore
                            exit or growth options privately.</p>
                        <p>
                            Our platform removes the traditional barriers to business transactions - high fees, geographical
                            limitations, and information asymmetry. By combining complete anonymity with zero cost barriers,
                            we've created the world's most accessible marketplace for business transactions. Whether you're
                            looking to sell, acquire, raise capital, or find partners, Mergersales provides the tools and
                            network to make it happen securely.
                        </p>
                        <a href="#" class="btn small-btn float-btn color-bg">How It Works</a>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-6">
                        <div class="about-img fl-wrap">
                            <img src="{{ asset('images/36.jpg') }}" class="respimg" alt="">
                            <div class="about-img-hotifer color-bg">
                                <p>Mergersales revolutionized how we approached selling our business. The confidential
                                    process gave us control we never had with traditional brokers.</p>
                                <h4>Former Tech CEO</h4>
                                <h5>Successfully Exited Business</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="color-bg small-padding">
        <div class="container">
            <div class="main-facts fl-wrap">
                <div class="inline-facts-wrap">
                    <div class="inline-facts">
                        <div class="milestone-counter">
                            <div class="stats animaper">
                                <div class="num" data-content="0" data-num="1256">0</div>
                            </div>
                        </div>
                        <h6>Businesses Listed</h6>
                    </div>
                </div>
                <div class="inline-facts-wrap">
                    <div class="inline-facts">
                        <div class="milestone-counter">
                            <div class="stats animaper">
                                <div class="num" data-content="0" data-num="5890">0</div>
                            </div>
                        </div>
                        <h6>Verified Buyers</h6>
                    </div>
                </div>
                <div class="inline-facts-wrap">
                    <div class="inline-facts">
                        <div class="milestone-counter">
                            <div class="stats animaper">
                                <div class="num" data-content="0" data-num="245">0</div>
                            </div>
                        </div>
                        <h6>Deals Completed</h6>
                    </div>
                </div>
                <div class="inline-facts-wrap">
                    <div class="inline-facts">
                        <div class="milestone-counter">
                            <div class="stats animaper">
                                <div class="num" data-content="0" data-num="85">0</div>
                            </div>
                        </div>
                        <h6>Countries Covered</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="svg-bg">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                y="0px" width="100%" height="100%" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMax slice">
                <defs>
                    <lineargradient id="bg">
                        <stop offset="0%" style="stop-color:rgba(255, 255, 255, 0.6)"></stop>
                        <stop offset="50%" style="stop-color:rgba(255, 255, 255, 0.1)"></stop>
                        <stop offset="100%" style="stop-color:rgba(255, 255, 255, 0.6)"></stop>
                    </lineargradient>
                    <path id="wave" stroke="url(#bg)" fill="none"
                        d="M-363.852,502.589c0,0,236.988-41.997,505.475,0
                                    s371.981,38.998,575.971,0s293.985-39.278,505.474,5.859s493.475,48.368,716.963-4.995v560.106H-363.852V502.589z" />
                </defs>
                <g>
                    <use xlink:href="#wave">
                        <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="10s"
                            calcMode="spline" values="270 230; -334 180; 270 230" keyTimes="0; .5; 1"
                            keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                    </use>
                    <use xlink:href="#wave">
                        <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="8s"
                            calcMode="spline" values="-270 230;243 220;-270 230" keyTimes="0; .6; 1"
                            keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                    </use>
                    <use xlink:href="#wave">
                        <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="6s"
                            calcMode="spline" values="0 230;-140 200;0 230" keyTimes="0; .4; 1"
                            keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                    </use>
                    <use xlink:href="#wave">
                        <animatetransform attributeName="transform" attributeType="XML" type="translate" dur="12s"
                            calcMode="spline" values="0 240;140 200;0 230" keyTimes="0; .4; 1"
                            keySplines="0.42, 0, 0.58, 1.0;0.42, 0, 0.58, 1.0" repeatCount="indefinite" />
                    </use>
                </g>
            </svg>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="section-title st-center fl-wrap">
                <h4>Core Values</h4>
                <h2>What Drives Us</h2>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-4">
                    <div class="team-item fl-wrap">
                        <div class="team-img fl-wrap">
                            <img src="{{ asset('images/12.jpg') }}" class="respimg" alt="">
                        </div>
                        <div class="team-content fl-wrap">
                            <h4>Confidentiality First</h4>
                            <h5>Our Foundation</h5>
                            <p>We believe business owners should control when and how their information is shared. Our
                                anonymous listing system protects identities while enabling meaningful connections.</p>
                        </div>
                        <div class="team-footer fl-wrap">
                            <ul class="team-social">
                                <li><a href="#" target="_blank"><i class="fab fa-shield-alt"></i></a></li>
                                <li><a href="#" target="_blank"><i class="fab fa-keycdn"></i></a></li>
                            </ul>
                            <a href="#" class="tolt tf-btn" data-microtip-position="top-right"
                                data-tooltip="Security Features"><i class="fal fa-lock"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-item fl-wrap">
                        <div class="team-img fl-wrap">
                            <img src="{{ asset('images/13.jpg') }}" class="respimg" alt="">
                        </div>
                        <div class="team-content fl-wrap">
                            <h4>Accessibility</h4>
                            <h5>Democratizing M&A</h5>
                            <p>By removing all fees, we open business transactions to everyone - from solo entrepreneurs to
                                large corporations. No financial barriers to exploring opportunities.</p>
                        </div>
                        <div class="team-footer fl-wrap">
                            <ul class="team-social">
                                <li><a href="#" target="_blank"><i class="fab fa-accessible-icon"></i></a></li>
                                <li><a href="#" target="_blank"><i class="fab fa-creative-commons-zero"></i></a>
                                </li>
                            </ul>
                            <a href="#" class="tolt tf-btn" data-microtip-position="top-right"
                                data-tooltip="Free Platform"><i class="fal fa-hand-holding-usd"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="team-item fl-wrap">
                        <div class="team-img fl-wrap">
                            <img src="{{ asset('images/14.jpg') }}" class="respimg" alt="">
                        </div>
                        <div class="team-content fl-wrap">
                            <h4>Global Perspective</h4>
                            <h5>Borderless Marketplace</h5>
                            <p>Business opportunities shouldn't be limited by geography. Our platform connects buyers and
                                sellers across continents, creating truly global deal flow.</p>
                        </div>
                        <div class="team-footer fl-wrap">
                            <ul class="team-social">
                                <li><a href="#" target="_blank"><i class="fab fa-globe"></i></a></li>
                                <li><a href="#" target="_blank"><i class="fab fa-connectdevelop"></i></a></li>
                            </ul>
                            <a href="#" class="tolt tf-btn" data-microtip-position="top-right"
                                data-tooltip="Global Network"><i class="fal fa-network-wired"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="parallax-section ps-bg video-section" data-scrollax-parent="true" id="sec2">
        <div class="bg-wrap">
            <div class="bg par-elem " data-bg="{{ asset('images/37.jpg') }}"
                data-scrollax="properties: { translateY: '30%' }"></div>
        </div>
        <div class="overlay"></div>
        <div class="container">
            <div class="video_section-title fl-wrap">
                <h2>How Mergersales Works</h2>
                <h4>Discover our simple, secure process for confidential business transactions<br>and join thousands of
                    successful users worldwide</h4>
            </div>
            <a href="https://www.youtube.com/watch?v=9v5Hx1dJJig&pp=ygUhaW50ZXJuYXRpb25hbCBidXNpbmVzcyBpbiBlbmdsaXNo"
                class="promo-link big_prom color-bg   image-popup"><i class="fas fa-play"></i></a>
        </div>
    </section>
    <section class="gray-bg ">
        <div class="container">
            <div class="section-title st-center fl-wrap">
                <h4>Success Stories</h4>
                <h2>What Our Users Say</h2>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="testimonials-slider-wrap">
            <div class="testimonials-slider">
                <div class="slick-item">
                    <div class="text-carousel-item fl-wrap">
                        <div class="text-carousel-item-header fl-wrap">
                            <div class="popup-avatar"><img src="{{ asset('images/16.jpg') }}" alt=""></div>
                            <div class="review-owner fl-wrap">Tech Entrepreneur</div>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"> </div>
                        </div>
                        <div class="text-carousel-content fl-wrap">
                            <p> "Mergersales allowed me to confidentially sell my SaaS business without exposing my identity
                                to competitors. Found a serious buyer within 3 weeks, and the platform being completely free
                                was unbelievable!"</p>
                            <a href="#" class="testim-link color-bg">Software Business Owner</a>
                        </div>
                    </div>
                </div>
                <div class="slick-item">
                    <div class="text-carousel-item fl-wrap">
                        <div class="text-carousel-item-header fl-wrap">
                            <div class="popup-avatar"><img src="{{ asset('images/17.jpg') }}" alt=""></div>
                            <div class="review-owner fl-wrap">Private Equity Investor</div>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="4"> </div>
                        </div>
                        <div class="text-carousel-content fl-wrap">
                            <p> "As a PE firm, we're always looking for quality deals. Mergersales gave us access to
                                businesses we wouldn't have found otherwise. The anonymous listings mean sellers are more
                                willing to list early-stage opportunities."</p>
                            <a href="#" class="testim-link color-bg">Investment Director</a>
                        </div>
                    </div>
                </div>
                <div class="slick-item">
                    <div class="text-carousel-item fl-wrap">
                        <div class="text-carousel-item-header fl-wrap">
                            <div class="popup-avatar"><img src="{{ asset('images/18.jpg') }}" alt=""></div>
                            <div class="review-owner fl-wrap">Manufacturing Business Owner</div>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="4"> </div>
                        </div>
                        <div class="text-carousel-content fl-wrap">
                            <p> "After 25 years running my manufacturing business, I wanted to retire without employees or
                                competitors knowing. Mergersales kept everything confidential while connecting me with 8
                                serious buyers. The deal closed in 4 months."</p>
                            <a href="#" class="testim-link color-bg">Industrial Business Seller</a>
                        </div>
                    </div>
                </div>
                <div class="slick-item">
                    <div class="text-carousel-item fl-wrap">
                        <div class="text-carousel-item-header fl-wrap">
                            <div class="popup-avatar"><img src="{{ asset('images/19.jpg') }}" alt=""></div>
                            <div class="review-owner fl-wrap">First-Time Acquirer</div>
                            <div class="listing-rating card-popup-rainingvis" data-starrating2="5"> </div>
                        </div>
                        <div class="text-carousel-content fl-wrap">
                            <p> "Looking to buy my first business was overwhelming. Mergersales made it simple - no fees,
                                straightforward listings, and the confidential approach meant sellers were more transparent.
                                Found and acquired an e-commerce business within 6 months."</p>
                            <a href="#" class="testim-link color-bg">New Business Owner</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
