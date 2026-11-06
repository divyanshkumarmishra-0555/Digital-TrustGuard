<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Digital TrustGuard</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- Our custom CSS -->
    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>


<body>


<!-- ================= NAVBAR ================= -->

<nav class="navbar navbar-expand-lg trust-navbar">

    <div class="container">

        <a class="navbar-brand trust-logo" href="#">
            <span class="logo-icon">
                <i class="bi bi-shield-check"></i>
            </span>

            Trust<span>Guard</span>
        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
        >
            <i class="bi bi-list"></i>
        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="#how-it-works">
                        How it works
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#features">
                        Features
                    </a>
                </li>
<li class="nav-item">
    <a
        class="nav-link"
        href="history.html"
    >
        <i class="bi bi-clock-history"></i>
        History
    </a>
</li>

                <li class="nav-item ms-lg-3">
                    <button class="btn btn-outline-light login-button">
                        Guest Mode
                    </button>
                </li>

            </ul>

        </div>

    </div>

</nav>



<!-- ================= HERO ================= -->

<section class="hero-section">

    <div class="container">

        <div class="hero-content text-center">

            <div class="hero-badge">

                <i class="bi bi-shield-lock-fill"></i>

                Digital Trust Assessment

            </div>


            <h1 class="hero-title">

                Can I
                <span>trust this?</span>

            </h1>


            <p class="hero-description">

                Analyze suspicious websites, messages,and QR codes with an explainable digital
                trust assessment.

            </p>


            <!-- ================= ANALYZER ================= -->

            <div class="analyzer-card">

                <!-- INPUT TABS -->

                <div class="input-tabs">

                    <button
                        class="input-tab active"
                        onclick="selectInput('url', this)"
                    >
                        <i class="bi bi-link-45deg"></i>
                        URL
                    </button>


                    <button
                        class="input-tab"
                        onclick="selectInput('text', this)"
                    >
                        <i class="bi bi-chat-left-text"></i>
                        Text
                    </button>


                    


                


                    <button
                        class="input-tab"
                        onclick="selectInput('qr', this)"
                    >
                        <i class="bi bi-qr-code-scan"></i>
                        QR Code
                    </button>

                </div>


                <!-- URL INPUT -->

                <div
                    id="url-input"
                    class="input-panel active-panel"
                >

                    <div class="input-label">

                        <i class="bi bi-link-45deg"></i>

                        Enter a suspicious URL

                    </div>


                    <div class="main-input-wrapper">

                        <i class="bi bi-globe2 input-icon"></i>

                        <input
                            type="text"
                            id="urlField"
                            class="main-input"
                            placeholder="https://example.com"
                        >

                    </div>

                </div>


                <!-- TEXT INPUT -->

                <div
                    id="text-input"
                    class="input-panel"
                >

                    <div class="input-label">

                        <i class="bi bi-chat-left-text"></i>

                        Paste a suspicious message

                    </div>


                    <div class="input-type-selector">

    <button
        type="button"
        id="textModeButton"
        class="input-type-button active"
        onclick="selectInputType('text')"
    >

        <i class="bi bi-chat-text"></i>

        Text / Message

    </button>


    <button
        type="button"
        id="urlModeButton"
        class="input-type-button"
        onclick="selectInputType('url')"
    >

        <i class="bi bi-link-45deg"></i>

        Website URL

    </button>

</div>


<textarea
    id="textField"
    class="main-textarea"
    placeholder="Paste a suspicious message, email, news article or social media post..."
></textarea>


                </div>


                <!-- IMAGE INPUT -->

                <div
                    id="image-input"
                    class="input-panel"
                >

                    <div class="input-label">

                        <i class="bi bi-image"></i>

                        Upload an image or screenshot

                    </div>


                    <div
                        class="upload-area"
                        onclick="document.getElementById('imageFile').click()"
                    >

                        <i class="bi bi-cloud-arrow-up upload-icon"></i>

                        <h5>Drop your image here</h5>

                        <p>
                            or click to browse
                        </p>

                        <span>
                            PNG, JPG, JPEG, WEBP
                        </span>

                    </div>


                    <input
                        type="file"
                        id="imageFile"
                        accept="image/*"
                        hidden
                    >
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
document.getElementById("imageFile").addEventListener("change", function () {

    if (this.files.length === 0) {
        return;
    }

    const file = this.files[0];

    const fileName = file.name;

    const uploadArea =
        this.closest(".input-panel")
            .querySelector(".upload-area");


    const image = new Image();


    image.onload = function () {

        uploadArea.querySelector("h5").textContent =
            fileName;

        uploadArea.querySelector("p").textContent =
            "Image selected successfully — " +
            image.width +
            " × " +
            image.height +
            " pixels";

    };


    image.src =
        URL.createObjectURL(file);

});
document.getElementById("imageFile").addEventListener("change", async function () {

    if (this.files.length === 0) {
        return;
    }

    const file = this.files[0];

    console.log("Starting OCR...");

    try {

        const result =
            await Tesseract.recognize(
                file,
                "eng"
            );

        extractedOCRText =
    result.data.text.trim();

console.log(
    "OCR extracted text:",
    extractedOCRText
);

    }

    catch (error) {

        console.error(
            "OCR error:",
            error
        );

    }

});
</script>
                </div>


            


                <!-- QR INPUT -->

                <div
                    id="qr-input"
                    class="input-panel"
                >

                    <div class="input-label">

                        <i class="bi bi-qr-code-scan"></i>

                        Upload a QR code

                    </div>


                    <div
                        class="upload-area"
                        onclick="document.getElementById('qrFile').click()"
                    >

                        <i class="bi bi-qr-code upload-icon"></i>

                        <h5>Upload your QR code</h5>

                        <p>
                            We'll decode the destination
                        </p>

                        <span>
                            PNG, JPG, JPEG
                        </span>

                    </div>


                    <input
                        type="file"
                        id="qrFile"
                        accept="image/*"
                        hidden
                    >
<script>
document.getElementById("qrFile").addEventListener("change", function () {

    if (this.files.length === 0) {
        return;
    }

    const file = this.files[0];

    const fileName = file.name;

    const uploadArea = this.closest(".input-panel")
        .querySelector(".upload-area");

    uploadArea.querySelector("h5").textContent =
        fileName;

    uploadArea.querySelector("p").textContent =
        "Checking for QR code...";


    const reader = new FileReader();


    reader.onload = function (event) {

        const image = new Image();


        image.onload = function () {

            const canvas =
                document.createElement("canvas");

            const context =
                canvas.getContext("2d");


            canvas.width =
                image.width;

            canvas.height =
                image.height;


            context.drawImage(
                image,
                0,
                0
            );


            const imageData =
                context.getImageData(
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );


            const qrCode =
                jsQR(
                    imageData.data,
                    imageData.width,
                    imageData.height
                );


            if (qrCode) {

    decodedQRData = qrCode.data;

    uploadArea.querySelector("p").textContent =
        "QR code detected successfully";

    console.log(
        "QR data:",
        decodedQRData
    );

}
            else {

                uploadArea.querySelector("p").textContent =
                    "This image is not a QR code";

                alert(
                    "The uploaded image is not a QR code. Please check your image."
                );

            }

        };


        image.src =
            event.target.result;

    };


    reader.readAsDataURL(file);

});
</script>
                </div>


                <!-- ANALYZE BUTTON -->

                <button
                    class="analyze-button"
                    onclick="startAnalysis()"
                >

                    <i class="bi bi-shield-check"></i>

                    Analyze with TrustGuard

                </button>


                <!-- TRUST INDICATORS -->

                <div class="trust-indicators">

                    <div>
                        <i class="bi bi-lock-fill"></i>
                        Private
                    </div>

                    <div>
                        <i class="bi bi-lightning-charge-fill"></i>
                        Fast
                    </div>

                    <div>
                        <i class="bi bi-search"></i>
                        Explainable
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- ================= HOW IT WORKS ================= -->

<section
    class="section"
    id="how-it-works"
>

    <div class="container">

        <div class="section-heading text-center">

            <span class="section-label">
                HOW IT WORKS
            </span>

            <h2>
                Three steps to digital trust
            </h2>

            <p>
                TrustGuard combines multiple signals to help
                you understand whether digital content deserves
                your trust.
            </p>

        </div>


        <div class="row g-4 mt-4">


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        01
                    </div>

                    <div class="step-icon">
                        <i class="bi bi-upload"></i>
                    </div>

                    <h4>
                        Submit
                    </h4>

                    <p>
                        Paste a URL or message, or upload
                        an image, video or QR code.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        02
                    </div>

                    <div class="step-icon">
                        <i class="bi bi-cpu"></i>
                    </div>

                    <h4>
                        Analyze
                    </h4>

                    <p>
                        TrustGuard examines security,
                        content and suspicious behavior signals.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        03
                    </div>

                    <div class="step-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>

                    <h4>
                        Understand
                    </h4>

                    <p>
                        Receive a trust score, evidence,
                        risk level and recommended actions.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ================= FEATURES ================= -->

<section
    class="section features-section"
    id="features"
>

    <div class="container">

        <div class="section-heading text-center">

            <span class="section-label">
                WHAT WE ANALYZE
            </span>

            <h2>
                One place for digital trust
            </h2>

        </div>


        <div class="row g-4 mt-4">


            <div class="col-md-6 col-lg-3">

                <div class="feature-card">

                    <i class="bi bi-link-45deg"></i>

                    <h5>
                        URL Security
                    </h5>

                    <p>
                        Identify suspicious domains,
                        URLs and impersonation signals.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="feature-card">

                    <i class="bi bi-fingerprint"></i>

                    <h5>
                        Scam Detection
                    </h5>

                    <p>
                        Detect urgency, threats,
                        payment and credential requests.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="feature-card">

                    <i class="bi bi-newspaper"></i>

                    <h5>
                        Content Analysis
                    </h5>

                    <p>
                        Examine claims, sources
                        and potentially misleading content.
                    </p>

                </div>

            </div>


            <div class="col-md-6 col-lg-3">

                <div class="feature-card">

                    <i class="bi bi-images"></i>

                    <h5>
                        Media Analysis
                    </h5>

                    <p>
                        Screen screenshots, images
                        and multimedia content.
                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- ================= FOOTER ================= -->

<footer class="trust-footer">

    <div class="container">

        <div class="footer-content">

            <div>

                <div class="trust-logo">

                    <span class="logo-icon">
                        <i class="bi bi-shield-check"></i>
                    </span>

                    Trust<span>Guard</span>

                </div>

                <p>
                    Helping people make safer digital decisions.
                </p>

            </div>


            <div class="footer-right">

                Digital TrustGuard

                <br>

                <span>
                    Built for a safer digital world.
                </span>

            </div>

        </div>

    </div>

</footer>



<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- TrustGuard JavaScript -->

<script>

    let selectedInput = "url";
    let decodedQRData = "";
    let extractedOCRText = "";
    function selectInput(type, button) {

        selectedInput = type;


        // Remove active state from all tabs

        document
            .querySelectorAll(".input-tab")
            .forEach(tab => {

                tab.classList.remove("active");

            });


        // Activate clicked tab

        button.classList.add("active");


        // Hide all input panels

        document
            .querySelectorAll(".input-panel")
            .forEach(panel => {

                panel.classList.remove("active-panel");

            });


        // Show selected panel

        document
            .getElementById(type + "-input")
            .classList.add("active-panel");

    }

    async function startAnalysis() {

    let content = "";


    /*
    --------------------------------------------------
    Get content from selected input
    --------------------------------------------------
    */

    if (selectedInput === "url") {

    content =
        document.getElementById(
            "urlField"
        ).value.trim();

}


else if (selectedInput === "text") {

    content =
        document.getElementById(
            "textField"
        ).value.trim();

}



    else if (
    selectedInput === "image" ||
    selectedInput === "qr"
) {

    const fileInput =
        document.getElementById(
            selectedInput === "image"
                ? "imageFile"
                : "qrFile"
        );


    if (
        !fileInput.files ||
        fileInput.files.length === 0
    ) {

        alert(
            "Please select a file to analyze."
        );

        return;

    }

}


else {

    alert(
        "Please select an input type."
    );

    return;

}

    /*
    --------------------------------------------------
    Validate
    --------------------------------------------------
    */

    if (
    (selectedInput === "url" || selectedInput === "text") &&
    content === ""
) {
    alert("Please enter something to analyze.");
    return;
}


    /*
    --------------------------------------------------
    Show loading message
    --------------------------------------------------
    */

    const button =
        document.querySelector(
            ".analyze-button"
        );


    button.innerHTML =
        '<i class="bi bi-hourglass-split"></i> Analyzing...';


    button.disabled = true;


    /*
    --------------------------------------------------
    Decide which analyzer to use
    --------------------------------------------------
    */

    const isWebsiteURL =
    selectedInput === "url" ||
    (
        selectedInput === "text" &&
        selectedInputType === "url"
    );


let analyzerURL;


if (isWebsiteURL) {

    analyzerURL =
        "api/url_api.php";

}

else {

    analyzerURL =
        "api/analyze.php";

}


    /*
    --------------------------------------------------
    Send data to PHP
    --------------------------------------------------
    */

    try {

        let response;


        /*
        --------------------------------------------------
        URL ANALYSIS
        --------------------------------------------------
        */

        if (isWebsiteURL) {

            response =
                await fetch(
                    analyzerURL,
                    {

                        method: "POST",

                        headers: {

                            "Content-Type":
                                "application/json"

                        },

                        body:
                            JSON.stringify({

                                url: content

                            })

                    }
                );

        }

 /*
--------------------------------------------------
IMAGE / QR ANALYSIS
--------------------------------------------------
*/

else if (
    selectedInput === "image" ||
    selectedInput === "qr"
) {

    /*
    --------------------------------------------------
    QR ANALYSIS
    --------------------------------------------------
    */

    if (selectedInput === "qr") {

        if (decodedQRData === "") {

            alert(
                "Please select a valid QR code image."
            );

            return;

        }


        /*
        --------------------------------------------------
        Send decoded QR data to URL analyzer
        --------------------------------------------------
        */

        if (
            decodedQRData.startsWith("http://") ||
            decodedQRData.startsWith("https://")
        ) {

            response =
                await fetch(
                    "api/url_api.php",
                    {

                        method: "POST",

                        headers: {

                            "Content-Type":
                                "application/json"

                        },

                        body:
    JSON.stringify({
        url:
            decodedQRData,

        input_type:
            "qr"
    })

                    }
                );

        }

        else {

            alert(
                "This QR code does not contain a website URL."
            );

            return;

        }

    }


    /*
    --------------------------------------------------
    IMAGE ANALYSIS
    --------------------------------------------------
    */

    else {

    /*
    --------------------------------------------------
    IMAGE OCR ANALYSIS
    --------------------------------------------------
    */

    if (extractedOCRText === "") {

        alert(
            "No readable text was detected in the image."
        );

        return;

    }


    /*
    --------------------------------------------------
    Send OCR text to existing text analyzer
    --------------------------------------------------
    */

    response =
        await fetch(
            "api/analyze.php",
            {

                method: "POST",

                headers: {

                    "Content-Type":
                        "application/json"

                },

                body:
                    JSON.stringify({

                        input_type:
                            "image",

                        input_content:
                            extractedOCRText

                    })

            }
        );

}

}
        /*
        --------------------------------------------------
        TEXT ANALYSIS
        --------------------------------------------------
        */

        else {

            response =
                await fetch(
                    analyzerURL,
                    {

                        method: "POST",

                        headers: {

                            "Content-Type":
                                "application/json"

                        },

                        body:
                            JSON.stringify({

                                input_type:
                                    selectedInputType,

                                input_content:
                                    content

                            })

                    }
                );

        }


        /*
        --------------------------------------------------
        Convert response to JSON
        --------------------------------------------------
        */

        const result =
    await response.json();





        /*
        --------------------------------------------------
        Handle error
        --------------------------------------------------
        */

        if (!result.success) {

            alert(
                result.message ||
                "Something went wrong."
            );

            return;

        }


        /*
        --------------------------------------------------
        Show TrustGuard dashboard
        --------------------------------------------------
        */

        showResults(result);


        /*
        --------------------------------------------------
        Developer console
        --------------------------------------------------
        */

        console.log(
            "TrustGuard result:",
            result
        );

    }


    catch (error) {

        console.error(
            "TrustGuard error:",
            error
        );


        alert(
           "Unable to connect to the TrustGuard analysis server."
        );

    }


    finally {

        button.innerHTML =
            '<i class="bi bi-shield-check"></i> Analyze with TrustGuard';


        button.disabled = false;

    }

}


function showResults(result) {
    /*
    --------------------------------------------------
    Analyzed content
    --------------------------------------------------
    */

    const analyzedContent =
        document.getElementById(
            "analyzedContent"
        );


    if (result.url) {

        analyzedContent.textContent =
            result.url;

    }

    else {

        analyzedContent.textContent =
            "Submitted content";

    }
    
        /*
    --------------------------------------------------
    Display formatted AI analysis
    --------------------------------------------------
    */

    renderAIAnalysis(
        result.ai_analysis,
        result.ai_provider,
        result.ai_model
    );

/*
--------------------------------------------------
Active analysis signals
--------------------------------------------------
*/

const activeSignals =
    document.getElementById(
        "activeSignals"
    );


activeSignals.innerHTML = "";


if (
    result.active_signals &&
    result.active_signals.length > 0
) {

    result.active_signals.forEach(
        function(signal) {

            const signalCard =
                document.createElement(
                    "div"
                );


            signalCard.className =
                "signal-card";


            const signalNames = {

                url:
                    "URL Security",

                scam:
                    "Scam / Phishing",

                source:
                    "Source Reputation",

                claims:
                    "Claim Analysis",

                media:
                    "Media Analysis"

            };


            signalCard.textContent =
                signalNames[signal] ||
                signal;


            activeSignals.appendChild(
                signalCard
            );

        }
    );

}

    /*
    --------------------------------------------------
    Show results section
    --------------------------------------------------
    */

    const resultsSection =
        document.getElementById(
            "resultsSection"
        );

    resultsSection.style.display =
        "block";


    /*
    --------------------------------------------------
    Trust score
    --------------------------------------------------
    */

    const trustScore =
        Number(result.trust_score);
/*
--------------------------------------------------
Trust score circle
--------------------------------------------------
*/

const scoreCircle =
    document.getElementById(
        "trustScoreCircle"
    );


const scoreDegrees =
    (trustScore / 100) * 360;


let scoreColor =
    "#22c55e";


if (trustScore <= 20) {

    scoreColor =
        "#ef4444";

}

else if (trustScore <= 40) {

    scoreColor =
        "#f97316";

}

else if (trustScore <= 70) {

    scoreColor =
        "#f59e0b";

}


scoreCircle.style.background =
    `conic-gradient(
        ${scoreColor} 0deg,
        ${scoreColor} ${scoreDegrees}deg,
        #e5e7eb ${scoreDegrees}deg,
        #e5e7eb 360deg
    )`;


    document.getElementById(
        "trustScore"
    ).textContent =
        trustScore;


    /*
    --------------------------------------------------
    Risk level
    --------------------------------------------------
    */

    const riskBadge =
        document.getElementById(
            "riskBadge"
        );


    riskBadge.textContent =
        result.risk_level;


    /*
    --------------------------------------------------
    Classification
    --------------------------------------------------
    */

    document.getElementById(
        "classification"
    ).textContent =
        result.classification;


    /*
    --------------------------------------------------
    Recommendation
    --------------------------------------------------
    */

    document.getElementById(
        "recommendation"
    ).textContent =
        result.recommendation;


    /*
    --------------------------------------------------
    Score progress
    --------------------------------------------------
    */

    const progress =
        document.getElementById(
            "scoreProgress"
        );


    progress.style.width =
        trustScore + "%";


    /*
    --------------------------------------------------
    Score color
    --------------------------------------------------
    */

    if (trustScore <= 20) {

        progress.style.background =
            "#ef4444";

    }

    else if (trustScore <= 40) {

        progress.style.background =
            "#f97316";

    }

    else if (trustScore <= 70) {

        progress.style.background =
            "#f59e0b";

    }

    else {

        progress.style.background =
            "#22c55e";

    }


    /*
    --------------------------------------------------
    Evidence container
    --------------------------------------------------
    */

    const container =
        document.getElementById(
            "evidenceContainer"
        );


    container.innerHTML = "";


    /*
    --------------------------------------------------
    Evidence cards
    --------------------------------------------------
    */

    if (
        result.evidence &&
        result.evidence.length > 0
    ) {

        result.evidence.forEach(
            function(item) {


                /*
                --------------------------------------
                Determine severity
                --------------------------------------
                */

                const severity =
                    (
                        item.severity ||
                        "Low"
                    ).toLowerCase();


                let icon =
                    "bi-info-circle";


                let color =
                    "#2563eb";


                let background =
                    "#dbeafe";


                if (
                    severity === "high"
                ) {

                    icon =
                        "bi-exclamation-triangle-fill";

                    color =
                        "#dc2626";

                    background =
                        "#fee2e2";

                }


                else if (
                    severity === "medium"
                ) {

                    icon =
                        "bi-exclamation-triangle";

                    color =
                        "#d97706";

                    background =
                        "#fef3c7";

                }


                else {

                    icon =
                        "bi-check-circle";

                    color =
                        "#2563eb";

                    background =
                        "#dbeafe";

                }


                /*
                --------------------------------------
                Create evidence card
                --------------------------------------
                */

                const card =
                    document.createElement(
                        "div"
                    );


                card.className =
                    "evidence-card";


                card.innerHTML = `

                    <div
                        class="evidence-icon"
                        style="
                            background:${background};
                            color:${color};
                        "
                    >

                        <i class="bi ${icon}"></i>

                    </div>


                    <div>

                        <h4>
                            ${formatCategory(
                                item.category
                            )}
                        </h4>


                        <p>

                            ${item.explanation}

                        </p>


                        <span
                            style="
                                color:${color};
                                font-weight:600;
                                font-size:13px;
                            "
                        >

                            ${severity.toUpperCase()} RISK

                        </span>

                    </div>

                `;


                container.appendChild(
                    card
                );

            }
        );

    }


    /*
    --------------------------------------------------
    No evidence
    --------------------------------------------------
    */

    else {

        container.innerHTML = `

            <div class="evidence-card">

                <div
                    class="evidence-icon"
                    style="
                        background:#dcfce7;
                        color:#16a34a;
                    "
                >

                    <i
                        class="bi bi-check-circle"
                    ></i>

                </div>


                <div>

                    <h4>
                        No major warning signs
                    </h4>


                    <p>

                        The current analyzer did not
                        detect significant suspicious
                        patterns.

                    </p>

                </div>

            </div>

        `;

    }


    /*
    --------------------------------------------------
    Scroll to results
    --------------------------------------------------
    */

    resultsSection.scrollIntoView({

        behavior: "smooth"

    });

}




function renderAIAnalysis(aiText, provider, model) {

    const section =
        document.getElementById("aiAnalysisSection");

    const content =
        document.getElementById("aiAnalysisContent");
    content.style.setProperty(
    "background-color",
    "#fef3c7",
    "important"
);

    const meta =
        document.getElementById("aiAnalysisMeta");

    content.textContent = "";
    meta.textContent = "";
    section.style.display = "none";

    if (!aiText) return;

    // Clean escaped Markdown and common HTML entities
    let text = String(aiText)
        .replace(/&#x20;|&#32;|&nbsp;/gi, " ")
        .replace(/&#39;|&apos;/gi, "'")
        .replace(/&quot;/gi, '"')
        .replace(/&amp;/gi, "&")
        .replace(/\\([\\`*_{}\[\]()#+\-.!|>])/g, "$1")
        .replace(/\r\n/g, "\n");

    const lines = text.split("\n");

    let currentTitle = "AI Findings";
    let currentLines = [];

    function cleanText(value) {

        return value
            .replace(/\*\*(.*?)\*\*/g, "$1")
            .replace(/__(.*?)__/g, "$1")
            .replace(/`([^`]*)`/g, "$1")
            .replace(/\[([^\]]+)\]\((https?:\/\/[^)]+)\)/g, "$1 ($2)")
            .trim();

    }

    function createCard(title, bodyLines) {

        const body = bodyLines.join("\n").trim();

        if (!body) return;

        const card = document.createElement("div");
        card.className = "evidence-card";
        card.style.display = "block";
        card.style.setProperty(
    "background-color",
    "#FFFCD1",
    "important"
);

        const heading = document.createElement("h4");
        heading.textContent = title;
        card.appendChild(heading);

        let bulletList = null;

        body.split("\n").forEach(line => {

            line = line.trim();

            if (!line) return;

            if (/^[-*•]\s+/.test(line)) {

                if (!bulletList) {
                    bulletList = document.createElement("ul");
                    card.appendChild(bulletList);
                }

                const item = document.createElement("li");

                item.textContent =
                    cleanText(line.replace(/^[-*•]\s+/, ""));

                bulletList.appendChild(item);

            } else {

                bulletList = null;

                const paragraph = document.createElement("p");
                paragraph.textContent = cleanText(line);
                card.appendChild(paragraph);

            }

        });

        content.appendChild(card);

    }

    lines.forEach(line => {

        const trimmed = line.trim();

        // Ignore the repeated report title
        if (/^#{1,6}\s*What the AI discovered/i.test(trimmed)) {
            return;
        }

        // Recognize bold or Markdown-style headings
        const headingMatch = trimmed.match(
            /^(?:#{1,6}\s*)?(?:\d+[.)]\s*)?\*\*(.+?)\*\*\s*$/
        );

        const plainHeadingMatch = trimmed.match(
            /^(?:#{1,6}\s*)?(?:\d+[.)]\s*)?([A-Z][A-Z\s/&-]{2,})$/
        );

        const newTitle =
            headingMatch
                ? cleanText(headingMatch[1])
                : plainHeadingMatch
                    ? plainHeadingMatch[1].trim()
                    : null;

        if (newTitle) {

            createCard(currentTitle, currentLines);

            currentTitle = newTitle;
            currentLines = [];

        } else {

            currentLines.push(
                trimmed.replace(/^\d+[.)]\s+/, "")
            );

        }

    });

    createCard(currentTitle, currentLines);

    meta.textContent =
        "Powered by " +
        (provider || "AI provider") +
        " | Model: " +
        (model || "Unavailable");

    section.style.display = "block";

}


function formatCategory(category) {

    return category
        .replaceAll("_", " ")
        .replace(
            /\b\w/g,
            function(letter) {

                return letter.toUpperCase();

            }
        );

}


function resetAnalysis() {

    const resultsSection =
        document.getElementById(
            "resultsSection"
        );


    resultsSection.style.display =
        "none";


    window.scrollTo({

        top: 0,

        behavior: "smooth"

    });

}
/* =====================================================
   TRUSTGUARD INPUT TYPE
===================================================== */

let selectedInputType = "text";


function selectInputType(type) {

    selectedInputType = type;


    const textButton =
        document.getElementById(
            "textModeButton"
        );


    const urlButton =
        document.getElementById(
            "urlModeButton"
        );


    const textField =
        document.getElementById(
            "textField"
        );


    /*
    --------------------------------------------------
    TEXT MODE
    --------------------------------------------------
    */

    if (type === "text") {

        textButton.classList.add(
            "active"
        );

        urlButton.classList.remove(
            "active"
        );


        textField.placeholder =
            "Paste a suspicious message, email, news article or social media post...";

    }


    /*
    --------------------------------------------------
    URL MODE
    --------------------------------------------------
    */

    else {

        urlButton.classList.add(
            "active"
        );

        textButton.classList.remove(
            "active"
        );


        textField.placeholder =
            "Paste a website URL, for example https://example.com";

    }

}

</script>

<!-- =====================================================
     TRUSTGUARD RESULTS DASHBOARD
====================================================== -->

<section
    id="resultsSection"
    class="results-section"
    style="display: none;"
>

    <div class="container">

        <div class="results-header">

    <span class="results-label">
        TRUSTGUARD ANALYSIS
    </span>

    <h2>
        Digital Trust Report
    </h2>

    <p>
        Here's what TrustGuard found in the submitted content.
    </p>

    <div
        id="analyzedContent"
        class="analyzed-content"
    >
    </div>

</div>



        <!-- TRUST SCORE -->

        <div class="trust-score-card">

            <div class="score-left">

                <div class="score-circle">

                    <div class="trust-score-circle" id="trustScoreCircle">

    <div class="trust-score-inner">

        <span id="trustScore">
            0
        </span>

        <small>
            /100
        </small>

    </div>

</div>


                </div>

            </div>


            <div class="score-details">

                <div
                    id="riskBadge"
                    class="risk-badge"
                >
                    ANALYZING
                </div>


                <h3 id="classification">
                    Analyzing...
                </h3>


                <p>
                    Digital Trust Score
                </p>


                <div class="score-bar">

                    <div
                        id="scoreProgress"
                        class="score-progress"
                    ></div>

                </div>

            </div>

        </div>


        <!-- EVIDENCE -->
<!-- =====================================================
     ANALYSIS SIGNALS
====================================================== -->

<div class="signals-section">

    <div class="section-title">

        <div>

            <span>
                ANALYSIS SIGNALS
            </span>

            <h3>
                What TrustGuard analyzed
            </h3>

        </div>

    </div>


    <div
        id="activeSignals"
        class="active-signals"
    >
    </div>

</div>

        <div class="evidence-section">

            <div class="section-title">

                <div>

                    <span>
                        EVIDENCE
                    </span>

                    <h3>
                        Why this result?
                    </h3>

                </div>

            </div>


            <div
                id="evidenceContainer"
                class="evidence-grid"
            >

            </div>

        </div>
        
        <!-- AI ANALYSIS -->

        <div
            id="aiAnalysisSection"
            class="evidence-section"
            style="display: none;"
        >

            <div class="section-title">

                <div>

                    <span>
                        AI-POWERED INSIGHTS
                    </span>

                    <h3>
                        What the AI discovered
                    </h3>

                </div>

            </div>

            <div
                id="aiAnalysisContent"
                class="evidence-card"
                style="white-space: pre-wrap;"
            ></div>

            <p
                id="aiAnalysisMeta"
                style="font-size: 12px; margin-top: 10px;"
            ></p>

        </div>



        <!-- RECOMMENDATION -->

        <div class="recommendation-card">

            <div class="recommendation-icon">

                <i class="bi bi-shield-exclamation"></i>

            </div>


            <div>

                <span>
                    RECOMMENDATION
                </span>

                <h3>
                    What should you do?
                </h3>

                <p id="recommendation">
                    Analyzing...
                </p>

            </div>

        </div>


        <!-- NEW ANALYSIS BUTTON -->

        <div class="new-analysis">

            <button
                onclick="resetAnalysis()"
                class="new-analysis-button"
            >

                <i class="bi bi-arrow-left"></i>

                Analyze Something Else

            </button>

        </div>

    </div>

</section>

</body>

</html>
