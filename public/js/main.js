function displayImageLink(target, image, url) {
    document.querySelector('#' + target + 'Image').src = image
    document.querySelector('#' + target + 'Url').href = url
}