@extend('layouts/frontend', ['title' => "Homepage"])

<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light site-navbar-target" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="/"><img class="strife-logo" height="35" width="35" src="/assets/images/strife-heading.png" alt="JCS"></a>
        <button class="navbar-toggler js-fh5co-nav-toggle fh5co-nav-toggle" type="button" data-toggle="collapse"
                data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav nav ml-auto">
                <li class="nav-item"><a href="#home-section" class="nav-link"><span>Home</span></a></li>
                <li class="nav-item"><a href="#about-section" class="nav-link"><span>About</span></a></li>
                <li class="nav-item"><a href="#services-section" class="nav-link"><span>Services</span></a></li>
                <li class="nav-item"><a href="#skills-section" class="nav-link"><span>Skills</span></a></li>
                <li class="nav-item"><a href="#projects-section" class="nav-link"><span>Projects</span></a></li>
                <li class="nav-item"><a href="#contact-section" class="nav-link"><span>Contact</span></a></li>
            </ul>
        </div>
    </div>
</nav>
<section id="home-section" class="hero">
    <div class="home-slider  owl-carousel">
        <div class="slider-item ">
            <div class="overlay"></div>
            <div class="container">
                <div class="row d-md-flex no-gutters slider-text align-items-end justify-content-end"
                     data-scrollax-parent="true">
                    <div class="one-third js-fullheight order-md-last img"
                         style="background-image:url(/assets/images/bg_1.png);">
                        <div class="overlay"></div>
                    </div>
                    <div class="one-forth d-flex  align-items-center ftco-animate"
                         data-scrollax=" properties: { translateY: '70%' }">
                        <div class="text">
                            <span class="subheading">Hello!</span>
                            <h1 class="mb-4 mt-3">I'm <span>Jesse Canonigo</span></h1>
                            <h2 class="mb-4">A Freelance Web Developer</h2>
                            <p><a href="#contact-section" class="btn btn-primary py-3 px-4">Hire me</a> <a href="#projects-section" class="btn btn-white btn-outline-white py-3 px-4">My Works</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="slider-item">
            <div class="overlay"></div>
            <div class="container">
                <div class="row d-flex no-gutters slider-text align-items-end justify-content-end"
                     data-scrollax-parent="true">
                    <div class="one-third js-fullheight order-md-last img"
                         style="background-image:url(/assets/images/bg_2.png);">
                        <div class="overlay"></div>
                    </div>
                    <div class="one-forth d-flex align-items-center ftco-animate"
                         data-scrollax=" properties: { translateY: '70%' }">
                        <div class="text">
                            <span class="subheading">Hello!</span>
                            <h1 class="mb-4 mt-3">I'm a <span>web developer</span> based in Philippines</h1>
                            <p><a href="#contact-section" class="btn btn-primary py-3 px-4">Hire me</a> <a href="#projects-section" class="btn btn-white btn-outline-white py-3 px-4">My
                                    works</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-about img ftco-section ftco-no-pb" id="about-section">
    <div class="container">
        <div class="row d-flex">
            <div class="col-md-6 col-lg-5 d-flex">
                <div class="img-about img d-flex align-items-stretch">
                    <div class="overlay"></div>
                    <div class="img d-flex align-self-stretch align-items-center"
                         style="background-image:url(/assets/images/pogi.png);">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-7 pl-lg-5 pb-5">
                <div class="row justify-content-start pb-3">
                    <div class="col-md-12 heading-section ftco-animate">
                        <h1 class="big">About</h1>
                        <h2 class="mb-4">About Me</h2>
                        <p>
                            I am a hobbyist, I enjoy coding with PHP Object Oriented Programming, Building CMS,
                            and developed and maintain my own PHP MVC Framework on my free time and I can work
                            on both frontend and backend and I love to see a beautifully written code 'coz I know
                            that better readability leads to greater scalability.

                            I have experience in Visual Basic and C# as well as
                            exploring a few Linux Distro.
                        </p>
                        <ul class="about-info mt-4 px-md-0 px-2">
                            <li class="d-flex"><span>Name:</span> <span>Jesse Canonigo</span></li>
                            <li class="d-flex"><span>Address:</span> <span>Manila, PH</span></li>
                            <li class="d-flex"><span>Zip code:</span> <span>1550</span></li>
                            <li class="d-flex"><span>Email:</span> <span>strifejeyz@gmail.com</span></li>
                            <li class="d-flex"><span>Phone: </span> <span>+(63)-965-600-3275</span></li>
                        </ul>
                    </div>
                </div>
                <div class="counter-wrap ftco-animate d-flex mt-md-3">
                    <div class="text">
                        <p class="mb-4">
                            <span class="number" data-number="30">0</span>+
                            <span>Projects completed</span>
                        </p>
                        <p><a href="/hire-me/cv" class="btn btn-primary py-3 px-3">Download CV</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section" id="services-section">
    <div class="container">
        <div class="row justify-content-center py-5 mt-5">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h1 class="big big-2">Services</h1>
                <h2 class="mb-4">Services</h2>
                <h3>How do we get started?</h3>
                <hr>
            </div>

            <div class="col-md-4">
                <h4>1. Answer Some Questions</h4>
                <div class="well">We will discuss requirements and specifications</div>
                <hr>
            </div>
            <div class="col-md-4">
                <h4>2. Analyse and Plan</h4>
                <div class="well">
                    I will look at your content, look at your competitors and industry and then
                    create the first version. At that point onwards, we take iterative feedback until everyone is happy.
                </div>
                <hr>
            </div>
            <div class="col-md-4">
                <h4>3. Deliver and Launch</h4>
                <div class="well">
                    Once your site is published, I make it easy to keep it updated.
                    You can either log in to your CMS account and make edits on your own or give me a call and I’ll do it for you.
                    Most plans include maintenance and updates. Ill be happy to make changes for you whenever inspiration strikes.
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 text-center d-flex ftco-animate">
                <a href="#" class="services-1">
							<span class="icon">
								<i class="flaticon-analysis"></i>
							</span>
                    <div class="desc">
                        <h3 class="mb-5">Web Design</h3>
                    </div>
                </a>
            </div>
            <div class="col-md-4 text-center d-flex ftco-animate">
                <a href="#" class="services-1">
							<span class="icon">
								<i class="flaticon-flasks"></i>
							</span>
                    <div class="desc">
                        <h3 class="mb-5">PSD to Responsive HTML</h3>
                    </div>
                </a>
            </div>
            <div class="col-md-4 text-center d-flex ftco-animate">
                <a href="#" class="services-1">
							<span class="icon">
								<i class="flaticon-ideas"></i>
							</span>
                    <div class="desc">
                        <h3 class="mb-5">Web Developer</h3>
                    </div>
                </a>
            </div>

            <div class="col-md-4 text-center d-flex ftco-animate">
                <a href="#" class="services-1">
							<span class="icon">
								<i class="flaticon-analysis"></i>
							</span>
                    <div class="desc">
                        <h3 class="mb-5">Desktop App Development</h3>
                    </div>
                </a>
            </div>
            <div class="col-md-4 text-center d-flex ftco-animate">
                <a href="#" class="services-1">
							<span class="icon">
								<i class="flaticon-flasks"></i>
							</span>
                    <div class="desc">
                        <h3 class="mb-5">Graphic Design</h3>
                    </div>
                </a>
            </div>
            <div class="col-md-4 text-center d-flex ftco-animate">
                <a href="#" class="services-1">
							<span class="icon">
								<i class="flaticon-ideas"></i>
							</span>
                    <div class="desc">
                        <h3 class="mb-5">Product Strategy</h3>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>


<section class="ftco-section" id="skills-section">
    <div class="container">
        <div class="row justify-content-center pb-5">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h1 class="big big-2">Skills</h1>
                <h2 class="mb-4">My Skills</h2>
                <p>
                    My experience in Programming/Web Development were mostly from Freelance where I can independently
                    develop,deploy, and maintain a system on my own.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>Photoshop</h3>
                    <div class="progress">
                        <div class="progress-bar color-1" role="progressbar" aria-valuenow="90"
                             aria-valuemin="0" aria-valuemax="100" style="width:90%">
                            <span>90%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>JavaScript</h3>
                    <div class="progress">
                        <div class="progress-bar color-2" role="progressbar" aria-valuenow="85"
                             aria-valuemin="0" aria-valuemax="100" style="width:85%">
                            <span>85%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>HTML5</h3>
                    <div class="progress">
                        <div class="progress-bar color-3" role="progressbar" aria-valuenow="90"
                             aria-valuemin="0" aria-valuemax="100" style="width:90%">
                            <span>90%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>CSS3/Bootstrap 3 or 4</h3>
                    <div class="progress">
                        <div class="progress-bar color-4" role="progressbar" aria-valuenow="90"
                             aria-valuemin="0" aria-valuemax="100" style="width:90%">
                            <span>90%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>PHP</h3>
                    <div class="progress">
                        <div class="progress-bar color-5" role="progressbar" aria-valuenow="95"
                             aria-valuemin="0" aria-valuemax="100" style="width:95%">
                            <span>95%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>CodeIgniter</h3>
                    <div class="progress">
                        <div class="progress-bar color-6" role="progressbar" aria-valuenow="80"
                             aria-valuemin="0" aria-valuemax="100" style="width:80%">
                            <span>80%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>Visual Basic</h3>
                    <div class="progress">
                        <div class="progress-bar color-6" role="progressbar" aria-valuenow="90"
                             aria-valuemin="0" aria-valuemax="100" style="width:90%">
                            <span>90%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate-box">
                <div class="progress-wrap ftco-animate">
                    <h3>C#</h3>
                    <div class="progress">
                        <div class="progress-bar color-6" role="progressbar" aria-valuenow="80"
                             aria-valuemin="0" aria-valuemax="100" style="width:80%">
                            <span>80%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="progress-wrap ftco-animate">
            <h3 style="text-align: center">Strife Framework</h3>
            <div class="progress">
                <div class="progress-bar color-6" role="progressbar" aria-valuenow="100"
                     aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    <span>100%</span>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="ftco-section ftco-project" id="projects-section">
    <div class="container">
        <div class="row justify-content-center pb-5">
            <div class="col-md-12 heading-section text-center ftco-animate">
                <h1 class="big big-2">Projects</h1>
                <h2 class="mb-4">My Projects</h2>
                <p></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="project img ftco-animate d-flex justify-content-center align-items-center"
                     style="background-image: url(/assets/images/project-4.jpg);">
                    <div class="overlay"></div>
                    <div class="text text-center p-4">
                        <h3><a href="http://strifeframework.ml">Strife Framework</a></h3>
                        <span>A Fast and Lightweight MVC Framework.</span>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="project img ftco-animate d-flex justify-content-center align-items-center"
                     style="background-image: url(/assets/images/project-5.jpg);">
                    <div class="overlay"></div>
                    <div class="text text-center p-4">
                        <h3><a href="https://github.com/strifejeyz/material-css">Material Bootstrap 3 UI</a></h3>
                        <span>A Bootstrap 3 components themed to suit your Material UI flavour.</span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="project img ftco-animate d-flex justify-content-center align-items-center"
                     style="background-image: url(/assets/images/project-1.jpg);">
                    <div class="overlay"></div>
                    <div class="text text-center p-4">
                        <h3><a href="https://github.com/strifejeyz/intranet-file-manager">Intranet File Manager</a></h3>
                        <span>Best used for Intranet connections that supports symlinks.</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="project img ftco-animate d-flex justify-content-center align-items-center"
                             style="background-image: url(/assets/images/project-2.jpg);">
                            <div class="overlay"></div>
                            <div class="text text-center p-4">
                                <h3><a href="https://github.com/strifejeyz/animator-css">CSS3 Animator Stylesheet</a></h3>
                                <span>A single CSS file that packs a punch for Animations!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="ftco-section ftco-no-pt ftco-no-pb ftco-counter img" id="section-counter">
    <div class="container">
        <div class="row d-md-flex align-items-center">
            <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18">
                    <div class="text">
                        <strong class="number" data-number="30">0</strong>
                        <span>30+ Completed Projects</span>
                    </div>
                </div>
            </div>
            <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18">
                    <div class="text">
                        <strong class="number" data-number="100">0</strong>
                        <span>100% Happy Customers</span>
                    </div>
                </div>
            </div>
            <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                <div class="block-18">
                    <div class="text">
                        <strong class="number" data-number="500">0</strong>
                        <span>500 Cup of coffee</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-hireme img margin-top" style="background-color: #0f0f0f;background-image:url('/assets/images/Entities.jpg')">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 ftco-animate text-center">
                <h2>I'm <span>Available</span> for freelancing</h2>
                <p>I can work on both Frontend/Backend, Desktop Applications, Product Strategy, and even help you sketch ideas to create your application.</p>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section contact-section ftco-no-pb" id="contact-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <h1 class="big big-2">Contact</h1>
                <h2 class="mb-4">Contact Me</h2>
                <p>Looking to do a personal or business website?</p>
            </div>
        </div>

        <div class="row d-flex contact-info mb-5">
            <div class="col-md-6 col-lg-3 d-flex ftco-animate">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-map-signs"></span>
                    </div>
                    <h3 class="mb-4">Address</h3>
                    <p>Boni Ave. Mandaluyong City, MNL, 1550</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 d-flex ftco-animate">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-phone2"></span>
                    </div>
                    <h3 class="mb-4">Contact Number</h3>
                    <p><a href="#">+(63)-965-600-3275</a></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 d-flex ftco-animate">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-paper-plane"></span>
                    </div>
                    <h3 class="mb-4">Email Address</h3>
                    <p><a href="mailto:strifejeyz@gmail.com">strifejeyz@gmail.com</a></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 d-flex ftco-animate">
                <div class="align-self-stretch box p-4 text-center">
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span class="icon-globe"></span>
                    </div>
                    <h3 class="mb-4">Website</h3>
                    <p><a href="/">jessestrife.cf</a></p>
                </div>
            </div>
        </div>

        <div class="row no-gutters block-9" id="contact-form">
            <div class="col-md-6 order-md-last d-flex">

                {!Form::open('/contact/send', ['class' => 'bg-light p-4 p-md-5 contact-form'])!}

                    <div class="form-group">
                        <?=Form::text('name', null, ['class'=>'form-control','placeholder'=>'Your Name']) ?>
                        <i class="text-danger">{{form_error('name')}}</i>
                    </div>
                    <div class="form-group">
                        <?=Form::text('email', null, ['class'=>'form-control','placeholder'=>'Your Email']) ?>
                        <i class="text-danger">{{form_error('email')}}</i>
                    </div>
                    <div class="form-group">
                        <?=Form::text('subject', null, ['class'=>'form-control','placeholder'=>'Subject']) ?>
                        <i class="text-danger">{{form_error('subject')}}</i>
                    </div>
                    <div class="form-group">
                        <?=Form::textarea('message', null, ['class'=>'form-control','rows'=>7,'placeholder'=>'Your Message']) ?>
                        <i class="text-danger">{{form_error('message')}}</i>
                    </div>

                    {!getflash('flash')!}

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary py-3 px-5">
                    </div>
                {!Form::close()!}

            </div>

            <div class="col-md-6 d-flex">
                <div class="img" style="background-image: url(/assets/images/about.jpg);"></div>
            </div>
        </div>
    </div>
</section>
@stop()