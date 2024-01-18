let i = 0;

function addNewContent() {

    let formContent = document.getElementById("form_content");

    let br = document.createElement("br");
    //let form = document.forms['create_post'];

    let type = document.getElementById("newContentType").value;
    
    // delete button doesnt work :( it adds a whitespace in the parameter
    // <button type="button" onclick="close("p1")" class="btn btn-danger">Delete</button>

    // new paragraph
    if (type == 'p') {

        let pTitleLabel = document.createElement("label");
        pTitleLabel.innerHTML = "Paragraph Header (optional)";
        let pTitle = document.createElement("input");
        pTitle.appendChild(pTitleLabel);
        pTitle.setAttribute("name", `form[${i}][pTitle]`)
        pTitle.setAttribute("type", "text");
        pTitle.setAttribute("class", "form-control bg-dark text-light");

        let pContentLabel = document.createElement("label");
        pContentLabel.innerHTML = "Paragraph Content";
        let pContent = document.createElement("textarea");
        pContent.setAttribute("name", `form[${i}][pContent]`)
        pContent.setAttribute("required", "");
        pContent.setAttribute("class", "form-control bg-dark text-light")
        pContent.appendChild(pContentLabel);

        
        formContent.appendChild(br.cloneNode());
        formContent.appendChild(pTitleLabel);
        formContent.appendChild(br.cloneNode());
        formContent.appendChild(pTitle);
        formContent.appendChild(br.cloneNode());
        formContent.appendChild(pContentLabel);
        formContent.appendChild(br.cloneNode());
        formContent.appendChild(pContent);
        formContent.appendChild(br.cloneNode());

    }

    // new unordered list
    if (type == 'ul' || type == "ol") {

        let ulLabel = document.createElement("label");
        if (type == "ul") {
            ulLabel.innerHTML = "Unordered List Content (separated by ,)";
            ulLabel.setAttribute("for", "ulContent");
        } else {
            ulLabel.innerHTML = "Ordered List Content (separated by ,)";
            ulLabel.setAttribute("for", "olContent");
        }

        let ulContent = document.createElement("textarea");
        ulContent.appendChild(ulLabel);
        if (type == "ul") {
            ulContent.setAttribute("name", `form[${i}][ulContent]`);
        } else {
            ulContent.setAttribute("name", `form[${i}][olContent]`);
        }
        
        ulContent.setAttribute("type", "text");
        ulContent.setAttribute("required", "");
        ulContent.setAttribute("class", "form-control bg-dark text-light");

        let br = document.createElement("br");

        formContent.appendChild(br.cloneNode());
        formContent.appendChild(ulLabel);
        formContent.appendChild(br.cloneNode());
        formContent.appendChild(ulContent);
        formContent.appendChild(br.cloneNode());

    }

    
    if (type == 't') {

        // tID++;
        // formContent += `
        
        
        
        // `;

    }

    i++;

    // document.getElementById("form_content").innerHTML = formContent;
    document.getElementById("newContentType").value = "";
    return;
}

function removeFormContent() {

    let formContent = document.getElementById("form_content");

    while (formContent.firstChild) {
        formContent.removeChild(formContent.firstChild)
    }
   
}