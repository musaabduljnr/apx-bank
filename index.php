<?php

$pageName  = "Home";

include_once("front/header.php");
require_once("include/userClass.php");
require_once("include/loginFunction.php");

?>
		<!-- Banner Section -->
		<div class="banner-section">
			<div class="main-slider-carousel owl-carousel owl-theme" style="height:620px;">

				<div class="slide" data-bg-image="<?= $web_url ?>/front/images/main-slider/intro-1.jpg" style="height:620px;">
					<div class="auto-container w-100">
						<div class="row clearfix">
							<!-- Content Column -->
							<div class="content-column col-lg-7 col-md-12 col-sm-12">
								<div class="inner-column">
									<h1> <span>Intelligent Banking for a Modern World</span> </h1>
									<div class="text" style="color:#1F1B44; font-size:16px; line-height:1.6; margin-bottom:25px;"><?= $pageTitle ?> delivers secure digital banking, high-yield wealth management, and seamless global transfers designed to empower personal and enterprise growth.</div>
									<div class="btn-box">
										<a href="./signup/verify-registration.php" class="theme-btn btn-style-one"><span class="txt">Open Account in Minutes</span></a>
										<a href="./login.php" class="theme-btn btn-style-one" style="background:#1F1B44; margin-left:12px;"><span class="txt">Online Banking Sign In</span></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
		<!-- End Banner Section -->

		<!-- CTA Section Start -->
		<div class="cta-section" style="background:#1F1B44;">
			<div class="auto-container">
				<div class="row align-items-center">
					<div class="col-lg-7">
						<!-- CTA Content Start -->
						<div class="cta-content">
							<h3 class="title">Dedicated Client Advisory & Priority Support <span class="text-bold">Available 24/7</span></h3>
							<p>Experience institutional-level service with dedicated relationship managers and round-the-clock technical assistance.</p>
						</div>
						<!-- CTA Content End -->
					</div>
					<div class="col-lg-5">
						<!-- CTA Phone Number Start -->
						<div class="cta-phone text-lg-end text-strat">
							<h2 class="title">+<?= $page['url_tel'] ?></h2>
						</div>
						<!-- CTA Phone Number Start -->
					</div>
				</div>
			</div>
		</div>
		<!-- CTA Section End -->

		<!-- About Section -->
		<div class="about-section">
			<div class="auto-container">
				<div class="inner-container">
					<div class="row align-items-center clearfix">
						<!-- Image Column -->
						<div class="image-column col-lg-6">
							<div class="about-image">
								<div class="about-inner-image">
									<img src="<?= $web_url ?>/front/images/about/home-about.png" alt="About APX Bank">
								</div>
							</div>
						</div>

						<!-- Content Column -->
						<div class="content-column col-lg-6 col-md-12 col-sm-12 mb-0">
							<div class="about-column">
								<div class="sec-title">
									<div class="title">About <?= $pageTitle ?></div>
									<h2>Building Financial Confidence <span>Through Innovation</span></h2>
								</div>
								<div class="text">
									<p>
										At <?= $pageTitle ?>, we combine time-tested financial integrity with high-performance digital infrastructure. From everyday checking and competitive high-yield savings to commercial lending and global treasury management, we provide individuals, entrepreneurs, and institutions with the security, liquidity, and agility required to thrive in today's economy.
									</p>
									<p>
										Every transaction is safeguarded by multi-layer encryption, real-time threat intelligence, and a resolute commitment to regulatory compliance and client privacy.
									</p>
								</div>
								<div class="signature">Executive Management | <?= $pageTitle ?></div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- End About Section -->

		<!-- Services Section -->
		<div class="services-section">
			<div class="auto-container">
				<div class="sec-title">
					<div class="title">Comprehensive Banking</div>
					<h2><span>Tailored Solutions</span> For Every Stage of Wealth</h2>
				</div>
				<div class="inner-container">
					<div class="row g-0">

						<!-- Service Block -->
						<div class="service-block col-lg-3 col-md-6 col-sm-12">
							<div class="inner-box">
								<div class="icon-box">
									<span class="icon ti-shield"></span>
								</div>
								<h5><a>Institutional Security</a></h5>
								<div class="text">Bank-grade end-to-end encryption, automated fraud detection, and 2FA authentication safeguarding your capital 24/7.</div>
							</div>
						</div>

						<!-- Service Block -->
						<div class="service-block col-lg-3 col-md-6 col-sm-12">
							<div class="inner-box">
								<div class="icon-box">
									<span class="icon ti-home"></span>
								</div>
								<h5><a>Commercial & Mortgage Lending</a></h5>
								<div class="text">Competitive fixed and variable rates, streamlined approvals, and customized financing for residential, retail, and industrial properties.</div>
							</div>
						</div>

						<!-- Service Block -->
						<div class="service-block col-lg-3 col-md-6 col-sm-12">
							<div class="inner-box">
								<div class="icon-box">
									<span class="fab fa-bitcoin"></span>
								</div>
								<h5><a>Digital Asset Integration</a></h5>
								<div class="text">Regulated gateways bridging traditional fiat banking with secure digital currency management, portfolio tracking, and instant settlement.</div>
							</div>
						</div>

						<!-- Service Block -->
						<div class="service-block col-lg-3 col-md-6 col-sm-12">
							<div class="inner-box">
								<div class="icon-box">
									<span class="icon ti-money"></span>
								</div>
								<h5><a>Business & Working Capital</a></h5>
								<div class="text">Flexible credit lines, equipment financing, and merchant cash advance options engineered to fuel uninterrupted corporate expansion.</div>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
		<!-- End Services Section -->

		<!-- Project Section -->
		<div class="project-section">
			<div class="auto-container">

				<!-- Sec Title -->
				<div class="sec-title">
					<div class="clearfix">
						<div class="pull-left">
							<h2>Proven Solutions for <span>Long-Term Prosperity</span></h2>
						</div>
					</div>
				</div>

			</div>
			<div class="outer-container">
				<div class="row clearfix">

					<!-- Column -->
					<div class="column col-lg-6 col-md-12 col-sm-12">
						<div class="row clearfix">

							<!-- Inner Column -->
							<div class="inner-column col-lg-6 col-md-6 col-sm-12">
								<div class="gallery-block">
									<div class="inner-box">
										<div class="image">
											<img src="<?= $web_url ?>/front/images/gallery/2.jpg" alt="" />
											<div class="overlay-box">
												<div class="overlay-inner">
													<h3><a><?= $pageTitle ?> Premier</a></h3>
													<div class="designation">Private & Institutional Banking</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Inner Column -->
							<div class="inner-column col-lg-6 col-md-6 col-sm-12">
								<div class="gallery-block">
									<div class="inner-box">
										<div class="image">
											<img src="<?= $web_url ?>/front/images/gallery/3.jpg" alt="" />
											<div class="overlay-box">
												<div class="overlay-inner">
													<h3><a>Corporate Treasury</a></h3>
													<div class="designation">Liquidity & Capital Optimization</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Inner Column -->
							<div class="inner-column col-lg-12 col-md-12 col-sm-12">
								<div class="gallery-block">
									<div class="inner-box">
										<div class="image">
											<img src="<?= $web_url ?>/front/images/gallery/4.jpg" alt="" />
											<div class="overlay-box">
												<div class="overlay-inner">
													<h3><a>High-Yield Portfolios</a></h3>
													<div class="designation">Strategic Wealth & Asset Preservation</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>

					<!-- Column -->
					<div class="column col-lg-6 col-md-12 col-sm-12">
						<div class="gallery-block">
							<div class="inner-box">
								<div class="image">
									<img src="<?= $web_url ?>/front/images/gallery/1.jpg" alt="" />
									<div class="overlay-box">
										<div class="overlay-inner">
											<h3><a>Mortgage & Asset Finance</a></h3>
											<div class="designation">Customized Real Estate & Term Credit</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- End Project Section -->

		<!-- Sponsors Section -->
		<div class="sponsors-section">
			<div class="auto-container">

				<div class="carousel-outer">
					<!--Sponsors Slider-->
					<ul class="sponsors-carousel owl-carousel owl-theme">
						<li>
							<div class="image-box"><a href="#"><img src="<?= $web_url ?>/front/images/clients/1.png" alt=""></a></div>
						</li>
						<li>
							<div class="image-box"><a href="#"><img src="<?= $web_url ?>/front/images/clients/2.png" alt=""></a></div>
						</li>
						<li>
							<div class="image-box"><a href="#"><img src="<?= $web_url ?>/front/images/clients/4.png" alt=""></a></div>
						</li>
						<li>
							<div class="image-box"><a href="#"><img src="<?= $web_url ?>/front/images/clients/5.png" alt=""></a></div>
						</li>
						<li>
							<div class="image-box"><a href="#"><img src="<?= $web_url ?>/front/images/clients/1.png" alt=""></a></div>
						</li>
						<li>
							<div class="image-box"><a href="#"><img src="<?= $web_url ?>/front/images/clients/2.png" alt=""></a></div>
						</li>
					</ul>
				</div>

			</div>
		</div>
		<!--End Sponsors Section-->

		<!-- Reputation Section Two -->
		<div class="reputation-section-two style-two">
			<div class="auto-container">
				<div class="row clearfix">

					<!-- Form Column -->
					<div class="form-column col-lg-5 col-md-12 col-sm-12">
						<div class="inner-column">
							<div class="form-boxed">
								<div class="text">The foundation of <?= $pageTitle ?> is built upon uncompromising fiduciary responsibility, client transparency, and institutional governance.</div>

								<div class="consult-form">
									<div class="sidebar-widget categories-blog">
										<div class="sidebar-title">
											<h4>Core Principles</h4>
										</div>
										<ul>
											<li><a>Uncompromising Integrity & Ethics</a></li>
											<li><a>Fiduciary Accountability</a></li>
											<li><a>Bank-Grade Privacy & Confidentiality</a></li>
											<li><a>Continuous Technological Innovation</a></li>
											<li><a>Radical Transparency in Lending</a></li>
											<li><a>Proactive Client Stewardship</a></li>
											<li><a>Community & Economic Empowerment</a></li>
											<li><a>Global Compliance & Governance</a></li>
										</ul>
									</div>
								</div>
							</div>

						</div>
					</div>

					<!-- Content Column -->
					<div class="content-column col-lg-7 col-md-12 col-sm-12">
						<div class="inner-column">
							<div class="sec-title">
								<div class="title">Why Choose <?= $pageTitle ?></div>
								<h2><span>Engineered for Security,</span><br> Committed to Your Growth</h2>
							</div>
							<div class="blocks-outer">

								<!-- Reputation Block -->
								<div class="reputation-block">
									<div class="inner-box">
										<h5>Our Vision</h5>
										<div class="text">To be the global benchmark in digital financial services, connecting individuals, businesses, and communities to frictionless capital, borderless opportunities, and resilient wealth creation.</div>
									</div>
								</div>

								<!-- Reputation Block -->
								<div class="reputation-block">
									<div class="inner-box">
										<h5>Our Mission</h5>
										<div class="text">The mission of <?= $pageTitle ?> is to empower financial freedom through innovative, responsible, and secure banking products. By leveraging modern cloud technology, transparent fee structures, and personalized financial advisory, we cultivate lasting value for our account holders, corporate partners, and the communities we serve.</div>
									</div>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- End Reputation Section -->

		<!-- Blog Section -->
		<div class="blog-section">
			<div class="auto-container">
				<div class="inner-container">
					<div class="clearfix row g-0">
						<!-- Column -->
						<div class="column col-lg-8 col-md-12 col-sm-12">

							<!-- News Block -->
							<div class="news-block">
								<div class="inner-box">
									<div class="clearfix">
										<!-- Image Column -->
										<div class="image-column col-lg-6 col-md-6 col-sm-12">
											<div class="inner-column">
												<div class="image">
													<a><img src="<?= $web_url ?>/front/images/resource/news-1.jpg" alt="" /></a>
												</div>
											</div>
										</div>
										<!-- Content Column -->
										<div class="content-column col-lg-6 col-md-6 col-sm-12">
											<div class="inner-column">
												<div class="arrow-one"></div>
												<div class="title">Residential & Commercial Financing</div>
												<h4><a>Unlocking property ownership with adaptive rate structures and expedited underwriting protocols.</a></h4>
												<div class="post-date">Market Insights by <span><?= $pageTitle ?> Mortgage Desk</span></div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- News Block -->
							<div class="news-block">
								<div class="inner-box">
									<div class="clearfix row g-0">
										<!-- Content Column -->
										<div class="content-column col-lg-6 col-md-6 col-sm-12 order-lg-1 order-2">
											<div class="inner-column">
												<div class="arrow-two"></div>
												<div class="title">Personal Wealth & Liquidity</div>
												<h4><a>Balancing cash flow agility and long-term capital preservation in fluctuating economic environments.</a></h4>
												<div class="post-date">Wealth Strategy by <span><?= $pageTitle ?> Advisory Team</span></div>
											</div>
										</div>
										<!-- Image Column -->
										<div class="image-column col-lg-6 col-md-6 col-sm-12 order-lg-2 order-1">
											<div class="inner-column">
												<div class="image">
													<a><img src="<?= $web_url ?>/front/images/resource/news-2.jpg" alt="" /></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

						<!-- Column -->
						<div class="column col-lg-4 col-md-12 col-sm-12">
							<!-- News Block Two -->
							<div class="news-block-two">
								<div class="inner-box">
									<div class="image">
										<a><img src="<?= $web_url ?>/front/images/resource/news-3.jpg" alt="" /></a>
										<div class="arrow"></div>
									</div>
									<div class="lower-content">
										<div class="title">Tax-Advantaged Accounts</div>
										<h4><a>Optimizing healthcare savings and pre-tax investments through structured Health Savings Accounts (HSAs).</a></h4>
										<div class="post-date">Financial Planning by <span><?= $pageTitle ?> Research</span></div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<!-- End Blog Section -->

<?php
include_once("front/footer.php");

?>