import { h } from 'preact'
import { Image } from './Image.jsx'
import { useEffect } from 'preact/hooks'

function Reader({ images, maxImageWidth, borderImage }) {
  useEffect(() => {
    let width = !maxImageWidth ? `100%` : `${maxImageWidth}px`
    document.documentElement.style.setProperty('--max-image-width', width)
  }, [])

  return (
    <div>
      <div className={`mx-auto max-image-width`} style={{ maxWidth: 'var(--max-image-width)' }}>
        {images.map((image, index) => {
          return <Image src={image.uri} key={index} alt={image.label} borderImage={borderImage} />
        })}
      </div>
    </div>
  )
}

export { Reader }
