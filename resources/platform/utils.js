function textToHtml(text){
    text = text.replace(/\n/g, "<br>");
    return text;
}

export { textToHtml };
