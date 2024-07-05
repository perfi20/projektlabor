let i = 0;

function addNewContent(type) {

    let formContent = document.getElementById("form_content");

    //let form = document.forms['create_post'];
    
    // delete button doesnt work :( it adds a whitespace in the parameter
    // <button type="button" onclick="close("p1")" class="btn btn-danger">Delete</button>

    // new header
    if (type == "h") {

        let br = document.createElement("br");

        let headerLabel = document.createElement("label");
        headerLabel.innerHTML = "Header Content"
        let header = document.createElement("input");
        header.appendChild(headerLabel);
        header.setAttribute("name", `form[${i}][header]`);
        header.setAttribute("type", "text");
        header.setAttribute("class", "form-control");
        header.setAttribute("required", "");

        let headerDelete = document.createElement("button");
        headerDelete.innerHTML = "";
        headerDelete.setAttribute("aria-label", "Close");
        headerDelete.setAttribute("type", "button");
        headerDelete.setAttribute("class", "btn-close");
        headerDelete.addEventListener("click", () => {

            formContent.removeChild(headerLabel);
            formContent.removeChild(header);
            formContent.removeChild(br);
            formContent.removeChild(headerDelete);

        });

        formContent.appendChild(headerLabel);
        formContent.appendChild(headerDelete);
        formContent.appendChild(header);
        formContent.appendChild(br);

    }

    // new paragraph
    if (type == 'p') {

        let br = document.createElement("br");

        let pContentLabel = document.createElement("label");
        pContentLabel.innerHTML = "Paragraph Content";
        let pContent = document.createElement("textarea");
        pContent.setAttribute("name", `form[${i}][pContent]`)
        pContent.setAttribute("required", "");
        pContent.setAttribute("class", "form-control")
        pContent.appendChild(pContentLabel);

        let paragraphDelete = document.createElement("button");
        paragraphDelete.innerHTML = "";
        paragraphDelete.setAttribute("aria-label", "Close");
        paragraphDelete.setAttribute("type", "button");
        paragraphDelete.setAttribute("class", "btn-close");
        paragraphDelete.addEventListener("click", () => {

            formContent.removeChild(pContentLabel);
            formContent.removeChild(pContent);
            formContent.removeChild(br);
            formContent.removeChild(paragraphDelete);

        });

        formContent.appendChild(pContentLabel);
        formContent.appendChild(paragraphDelete);
        formContent.appendChild(pContent);
        formContent.appendChild(br);

    }

    // new unordered list
    if (type == 'ul' || type == "ol") {

        let br = document.createElement("br");

        let listLabel = document.createElement("label");
        if (type == "ul") {
            listLabel.innerHTML = "Unordered List Content (separated by ,)";
            listLabel.setAttribute("for", "ulContent");
        } else {
            listLabel.innerHTML = "Ordered List Content (separated by ,)";
            listLabel.setAttribute("for", "olContent");
        }

        let listContent = document.createElement("textarea");
        listContent.appendChild(listLabel);
        if (type == "ul") {
            listContent.setAttribute("name", `form[${i}][ulContent]`);
        } else {
            listContent.setAttribute("name", `form[${i}][olContent]`);
        }
        
        listContent.setAttribute("type", "text");
        listContent.setAttribute("required", "");
        listContent.setAttribute("class", "form-control");

        let listDelete = document.createElement("button");
        listDelete.innerHTML = "";
        listDelete.setAttribute("aria-label", "Close");
        listDelete.setAttribute("type", "button");
        listDelete.setAttribute("class", "btn-close");
        listDelete.addEventListener("click", () => {

            formContent.removeChild(listLabel);
            formContent.removeChild(listContent);
            formContent.removeChild(br);
            formContent.removeChild(listDelete);

        });

        formContent.appendChild(listLabel);
        formContent.appendChild(listDelete);
        formContent.appendChild(listContent);
        formContent.appendChild(br);
        
    }

    
    if (type == 't') {

        let br = document.createElement("br");

        //let j=1;

        let tableHeadsLabel = document.createElement("label");
        tableHeadsLabel.innerHTML = "Table head names (separated by ,)";
        tableHeadsLabel.setAttribute("for", "tableHeads");
        let tableHeadNames = document.createElement("input");
        tableHeadNames.setAttribute("type", "text");
        tableHeadNames.setAttribute("name", `form[${i}][tableHeads]`);
        tableHeadNames.setAttribute("required", "");
        tableHeadNames.setAttribute("class", "form-control");

        let tableRowLabel = document.createElement("label");
        tableRowLabel.innerHTML = `Table row contents (rows separated by - , data separated by ,)`;
        tableRowLabel.setAttribute("for", "tableRows");
        let tableRow = document.createElement("input");
        tableRow.setAttribute("type", "text");
        tableRow.setAttribute("name", `form[${i}][tableRows]`);
        tableRow.setAttribute("required", "");
        tableRow.setAttribute("class", "form-control");

        let tableDelete = document.createElement("button");
        tableDelete.innerHTML = "";
        tableDelete.setAttribute("aria-label", "Close");
        tableDelete.setAttribute("type", "button");
        tableDelete.setAttribute("class", "btn-close");
        tableDelete.addEventListener("click", () => {

            formContent.removeChild(tableHeadsLabel);
            formContent.removeChild(tableHeadNames);
            formContent.removeChild(tableRowLabel);
            formContent.removeChild(tableRow);
            formContent.removeChild(br);
            formContent.removeChild(tableDelete);

        });

        // let generateRowBtn = document.createElement("button");
        // generateRowBtn.innerHTML = "Generate table";
        // generateRowBtn.setAttribute("type", "button");
        // generateRowBtn.setAttribute("class", "btn btn-outline-primary");
        // generateRowBtn.addEventListener("click", () => {

        //     let tableRowLabel = document.createElement("label");
        //     tableRowLabel.innerHTML = `Table ${j}. row content (separated by ,)`;
        //     tableRowLabel.setAttribute("for", "tableRows");
        //     let tableRow = document.createElement("input");
        //     tableRow.setAttribute("type", "text");
        //     tableRow.setAttribute("name", `form[$i][tableRows]`);
        //     tableRow.setAttribute("required", "");
        //     tableRow.setAttribute("class", "form-control bg-dark text-light");

        //     formContent.appendChild(tableRowLabel);
        //     formContent.appendChild(br.cloneNode());
        //     formContent.appendChild(tableRow);
        //     formContent.appendChild(br.cloneNode());

        //     j++;
        // });


        formContent.appendChild(tableHeadsLabel);
        formContent.appendChild(tableDelete);
        formContent.appendChild(tableHeadNames);

        formContent.appendChild(tableRowLabel);
        formContent.appendChild(tableRow);
        formContent.appendChild(br);
        
        // formContent.appendChild(generateRowBtn);
        // formContent.appendChild(br.cloneNode());
        // formContent.appendChild(br.cloneNode());
    }

    if (type == 'pic') {

        let br = document.createElement("br");

        let picLabel = document.createElement("label");
        picLabel.innerHTML = "Picture URL";
        picLabel.setAttribute("for", "picture");
        let picture = document.createElement("input");
        picture.setAttribute("type", "file");
        picture.setAttribute("accept", "image/*");
        picture.setAttribute("name", `picture[]`);
        picture.setAttribute("class", "form-control");
        picture.setAttribute("required", "");

        let pictureDelete = document.createElement("button");
        pictureDelete.innerHTML = "";
        pictureDelete.setAttribute("aria-label", "Close");
        pictureDelete.setAttribute("type", "button");
        pictureDelete.setAttribute("class", "btn-close");
        pictureDelete.addEventListener("click", () => {

            formContent.removeChild(picLabel);
            formContent.removeChild(picture);
            formContent.removeChild(br);
            formContent.removeChild(pictureDelete);

        });

        formContent.appendChild(picLabel);
        formContent.appendChild(pictureDelete);
        formContent.appendChild(picture);
        formContent.appendChild(br);

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