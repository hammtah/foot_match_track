<style>
    /* From Uiverse.io by zanina-yassine */
    .comments-card {
        width: 600px;
        height: fit-content;
        /* background-color: white; */
        background-color: var(--bg-dark);
        box-shadow: 0px 187px 75px rgba(0, 0, 0, 0.01), 0px 105px 63px rgba(0, 0, 0, 0.05), 0px 47px 47px rgba(0, 0, 0, 0.09), 0px 12px 26px rgba(0, 0, 0, 0.1), 0px 0px 0px rgba(0, 0, 0, 0.1);
        border-radius: 17px 17px 27px 27px;
        font-family: Inter, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", Segoe UI Symbol, "Noto Color Emoji"

        }

    .title {
        width: 100%;
        height: 50px;
        position: relative;
        display: flex;
        align-items: center;
        padding-left: 20px;
        border-bottom: 1px solid #f1f1f1;
        font-weight: 700;
        font-size: 13px;
        /* color: #47484b; */
        color: white;
    }

    .title::after {
        content: '';
        width: 8ch;
        height: 1px;
        position: absolute;
        bottom: -1px;
        /* background-color: #47484b; */
    }

    .comments {
        display: grid;
        grid-template-columns: 35px 1fr;
        gap: 20px;
        padding: 20px;
    }

    .comment-react {
        width: 35px;
        height: fit-content;
        display: grid;
        grid-template-columns: auto;
        margin: 0;
        background-color: #f1f1f1;
        border-radius: 5px;
    }

    .comment-react button {
        width: 35px;
        height: 35px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: 0;
        outline: none;
    }

    .comment-react button:after {
        content: '';
        width: 40px;
        height: 40px;
        position: absolute;
        left: -2.5px;
        top: -2.5px;
        background-color: #f5356e;
        border-radius: 50%;
        z-index: 0;
        transform: scale(0);
    }

    .comment-react button svg {
        position: relative;
        z-index: 9;
    }

    .comment-react button:hover:after {
        animation: ripple 0.6s ease-in-out forwards;
    }

    .comment-react button:hover svg {
        fill: #f5356e;
    }

    .comment-react button:hover svg path {
        stroke: #f5356e;
        fill: #f5356e;
    }

    .comment-react hr {
        width: 80%;
        height: 1px;
        background-color: #dfe1e6;
        margin: auto;
        border: 0;
    }

    .comment-react span {
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-size: 13px;
        font-weight: 600;
        color: #707277;
    }

    .comment-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 0;
        margin: 0;
    }

    .comment-container .user {
        display: grid;
        grid-template-columns: 40px 1fr;
        gap: 10px;
    }

    .comment-container .user .user-pic {
        width: 40px;
        height: 40px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f1f1f1;
        border-radius: 50%;
    }

    .comment-container .user .user-pic:after {
        content: '';
        width: 9px;
        height: 9px;
        position: absolute;
        right: 0px;
        bottom: 0px;
        border-radius: 50%;
        background-color: #0fc45a;
        border: 2px solid #ffffff;
    }

    .comment-container .user .user-info {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 3px;
    }

    .comment-container .user .user-info span {
        font-weight: 700;
        font-size: 12px;
        /* color: #47484b; */
        color: var(--primary-color);
    }

    .comment-container .user .user-info p {
        font-weight: 600;
        font-size: 10px;
        color: #acaeb4;
    }

    .comment-container .comment-content {
        font-size: 12px;
        line-height: 16px;
        font-weight: 600;
        /* color: #5f6064; */
        color: white;
    }

    .text-box {
        width: 100%;
        height: fit-content;
        background-color: #f1f1f1;
        padding: 8px;
        background-color: var(--bg-card);
    }

    .text-box .box-container {
        /* background-color: #ffffff; */
        border-radius: 8px 8px 21px 21px;
        padding: 8px;
        background-color: var(--bg-card);

    }

    .text-box textarea {
        width: 100%;
        height: 70px;
        resize: none;
        border: 0;
        border-radius: 6px;
        padding: 12px 12px 10px 12px;
        font-size: 13px;
        outline: none;
        caret-color: #0a84ff;
        background-color: var(--bg-card);
        color: white;
    }

    .text-box .formatting {
        display: grid;
        grid-template-columns: auto auto auto auto auto 1fr;
    }

    .formatting {
        margin-top: 1em;
    }

    .text-box .formatting button {
        width: 30px;
        height: 30px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border-radius: 50%;
        border: 0;
        outline: none;
    }

    .text-box .formatting button:hover {
        background-color: #f1f1f1;
    }

    .text-box .formatting .send {
        width: 30px;
        height: 30px;
        background-color: #0a84ff;
        margin: 0 0 0 auto;
    }

    .text-box .formatting .send:hover {
        background-color: #026eda;
    }

    #hide-comments {
        background: none;
        border: none;
        cursor: pointer;
        color: #707277;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px auto;
        }

    #hide-comments svg {
        transition: transform 0.3s ease;
    }

    #hide-comments:hover svg {
        transform: translateY(-3px);
    }

    #show-more {
        background: none;
        border: none;
        cursor: pointer;
        color: #707277;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px auto;
        }

    #show-more svg {
        transition: transform 0.3s ease;
    }

    #show-more:hover svg {
        transform: translateY(-3px);
    }

    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 0.6;
        }

        100% {
            transform: scale(1);
            opacity: 0;
        }
    }
</style>
<div class="comments-card" style="position: relative;">
    <span class="title">Comments</span>

    <section id="comments-container">
        <!-- Comments will be dynamically rendered here -->
    </section>

    <div id="show-more-container" style="text-align: center; margin-top: 10px;">
        <button id="show-more" style="background: none; border: none; cursor: pointer;">
            <svg fill="none" viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#707277" d="M6 9L12 15L18 9"></path>
            </svg>
        </button>
        <button id="hide-comments" style="background: none; border: none; cursor: pointer; display: none;">
            <svg fill="none" viewBox="0 0 24 24" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2" stroke="#707277" d="M6 15L12 9L18 15"></path>
            </svg>
        </button>
    </div>
    
    <?php if (isset($_SESSION['id'])): ?>
        <form class="text-box" action="#" method="POST" id="comment-form">
            <div class="box-container">
                <textarea placeholder="Comment" id="textarea"></textarea>
                <div>
                    <div class="formatting">
                        <button type="submit" class="send" title="Send" id="send">
                            <svg fill="none" viewBox="0 0 24 24" height="18" width="18" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" stroke="#ffffff"
                                    d="M12 5L12 20"></path>
                                <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" stroke="#ffffff"
                                    d="M7 9L11.2929 4.70711C11.6262 4.37377 11.7929 4.20711 12 4.20711C12.2071 4.20711 12.3738 4.37377 12.7071 4.70711L17 9">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="text-box" style="padding: 10px; background-color: var(--bg-card); position: relative; filter: blur(4px);">
        <style>
            .comments-card::after {
                content: "You must be logged in to comment.";
                position: absolute;
                top: 51.7%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: #707277;
                font-size: 14px;
                filter: none;
                text-align: center;
            }
        </style>
            <!-- <p style="color: #707277; font-size: 14px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                You must be logged in to comment.
            </p> -->
            <form class="text-box" action="#" method="POST" id="comment-form">
                <div class="box-container">
                    <textarea placeholder="Comment" id="textarea" disabled></textarea>
                    <div>
                        <div class="formatting">
                            <button type="submit" class="send" title="Send" id="send" disabled>
                                <svg fill="none" viewBox="0 0 24 24" height="18" width="18" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" stroke="#ffffff"
                                        d="M12 5L12 20"></path>
                                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" stroke="#ffffff"
                                        d="M7 9L11.2929 4.70711C11.6262 4.37377 11.7929 4.20711 12 4.20711C12.2071 4.20711 12.3738 4.37377 12.7071 4.70711L17 9">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>


<!-- js -->
<!-- <script type="module" src="comments-script.js"></script> -->

<script>
    document.addEventListener("DOMContentLoaded", (event) => {
        const textarea = document.getElementById("textarea");
        getComments().then((data) => renderComments(data));

        document.getElementById("comment-form").addEventListener('submit', (e) => {
            e.preventDefault();
            if (textarea.value !== "") publish().then(() => {
                textarea.value = "";
                getComments().then((data) => renderComments(data));
            });
        })

    });


    let commentsData = []; // Store all comments
    let visibleComments = 4; // Number of comments to show initially

    function renderComments(data) {
        commentsData = data.comments; // Store all comments
        updateVisibleComments();
    }

    function updateVisibleComments() {
        const commentsToRender = commentsData.slice(0, visibleComments); // Get the visible comments
        let renderString = "";

        commentsToRender.forEach(comment => {
            renderString += `
            <div class="comments">
                <div class="comment-react"></div>
                <div class="comment-container">
                    <div class="user">
                        <div class="user-pic">
                            <svg fill="none" viewBox="0 0 24 24" height="20" width="20" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linejoin="round" fill="#707277" stroke-linecap="round" stroke-width="2" stroke="#707277" d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z"></path>
                                <path stroke-width="2" fill="#707277" stroke="#707277" d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z"></path>
                            </svg>
                        </div>
                        <div class="user-info">
                            <span>${comment.nom}</span>
                            <p>${formatDate(comment.date_comment)}</p>
                        </div>
                    </div>
                    <p class="comment-content">${comment.comment}</p>
                </div>
                
            </div>
        `;
        });

        document.getElementById("comments-container").innerHTML = renderString;

        // Show or hide the "Show More" and "Hide Comments" buttons
        const showMoreButton = document.getElementById("show-more");
        const hideCommentsButton = document.getElementById("hide-comments");

        if (visibleComments >= commentsData.length) {
            showMoreButton.style.display = "none";
        } else {
            showMoreButton.style.display = "block";
        }

        if (visibleComments > 4) {
            hideCommentsButton.style.display = "block";
        } else {
            hideCommentsButton.style.display = "none";
        }
    }

    // Event listener for the "Show More" button
    document.getElementById("show-more").addEventListener("click", () => {
        visibleComments += 4; // Increase the number of visible comments
        updateVisibleComments();
    });

    // Event listener for the "Hide Comments" button
    document.getElementById("hide-comments").addEventListener("click", () => {
        visibleComments = 4; // Reset to show only the initial 8 comments
        updateVisibleComments();
    });

    // function renderComments(data){
    //     const comments = data.comments;
    //     renderString = "";
    //     comments.forEach(comment => {
    //         renderString += `
    //               <div class="comments">
    //     <div class="comment-react">
    //     </div>
    //     <div class="comment-container">
    //       <div class="user">
    //         <div class="user-pic">
    //           <svg fill="none" viewBox="0 0 24 24" height="20" width="20" xmlns="http://www.w3.org/2000/svg">
    //             <path stroke-linejoin="round" fill="#707277" stroke-linecap="round" stroke-width="2" stroke="#707277" d="M6.57757 15.4816C5.1628 16.324 1.45336 18.0441 3.71266 20.1966C4.81631 21.248 6.04549 22 7.59087 22H16.4091C17.9545 22 19.1837 21.248 20.2873 20.1966C22.5466 18.0441 18.8372 16.324 17.4224 15.4816C14.1048 13.5061 9.89519 13.5061 6.57757 15.4816Z"></path>
    //             <path stroke-width="2" fill="#707277" stroke="#707277" d="M16.5 6.5C16.5 8.98528 14.4853 11 12 11C9.51472 11 7.5 8.98528 7.5 6.5C7.5 4.01472 9.51472 2 12 2C14.4853 2 16.5 4.01472 16.5 6.5Z"></path>
    //           </svg>
    //         </div>
    //         <div class="user-info">
    //           <span>${comment.nom}</span>
    //           <p>${formatDate(comment.date_comment)}</p>
    //         </div>
    //       </div>
    //       <p class="comment-content">
    //         ${comment.comment}
    //       </p>
    //     </div>
    //   </div>
    //         `
    //     });

    //     document.getElementById("comments-container").innerHTML = renderString;
    // }
    async function getComments() {
        // hadi makhdamach
        // const id_match = new URLSearchParams(window.location.search).get('id_match'); 
        const id_match=<?php echo json_encode($match_id); ?>;
        let result = await fetch(`comments-serv.php?id_match=${id_match}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            }
        });
        let data = await result.json();
        return data;
    }

    async function publish() {
        // const id_match = new URLSearchParams(window.location.search).get('id_match');
        const id_match=<?php echo json_encode($match_id); ?>;

        // console.log("hgjasdbjashd")

        // console.log(id_match)
        const comment = textarea.value;

        if (!id_match || !comment) {
            console.error("Missing id_match or comment");
            return;
        }

        const data = new URLSearchParams();
        data.append("id_match", id_match);
        data.append("comment", comment);

        try {
            const result = await fetch(`comments-serv.php`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: data.toString(),
            });

            const response = await result.json();
            console.log(response);
        } catch (error) {
            console.error("Error:", error);
        }
    }

    // utility
    function formatDate(idate) {
        const date = new Date(idate);

        // Get day of the week and month name
        const options = {
            weekday: 'long',
            month: 'long',
            day: 'numeric'
        };
        let formattedDate = date.toLocaleDateString('en-US', options);

        // Add ordinal suffix (st, nd, rd, th)
        const day = date.getDate();
        const suffix = getOrdinalSuffix(day);
        formattedDate = formattedDate.replace(/\d+/, day + suffix);

        // Get time in 12-hour format
        const formattedTime = date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });

        return `${formattedDate} at ${formattedTime}`;
    }
    // Function to get the correct ordinal suffix
    function getOrdinalSuffix(day) {
        if (day >= 11 && day <= 13) return "th"; // Special case for 11th, 12th, 13th
        switch (day % 10) {
            case 1:
                return "st";
            case 2:
                return "nd";
            case 3:
                return "rd";
            default:
                return "th";
        }
    }

    // async function addLike(commentId){
    //     const data = new URLSearchParams();
    //     data.append("id_comment", commentId);

    //     try {
    //         const result = await fetch(`comments-serv.php`, {
    //             method: "POST",
    //             headers: {
    //                 "Content-Type": "application/x-www-form-urlencoded",
    //             },
    //             body: data.toString(),
    //         });

    //         const response = await result.json();
    //         console.log(response);
    //     } catch (error) {
    //         console.error("Error:", error);
    //     }
    // }
</script>