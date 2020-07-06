import { useEffect } from 'react'

import { Image } from './Image'
import { EndOfPage } from './EndOfPage'

function Reader({ images, maxImageWidth, borderImage }) {
  useEffect(() => {
    let width = !maxImageWidth ? `100%` : `${maxImageWidth}px`
    document.documentElement.style.setProperty('--max-image-width', width)
  }, [maxImageWidth])

  return (
    <div>
      <div className={`mx-auto max-image-width`} style={{ maxWidth: 'var(--max-image-width)' }}>
        {images.map((image, index) => {
          return <Image src={image.uri} key={index} alt={image.label} borderImage={borderImage} />
        })}
      </div>
      <EndOfPage />
    </div>
  )
}

export { Reader }
