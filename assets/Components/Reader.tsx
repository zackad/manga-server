import { h } from 'preact'
import { useEffect } from 'preact/hooks'

import { Image } from 'App/Components/Image'
import { EndOfPage } from 'App/Components/EndOfPage'
import { EntryData } from 'App/types/EntryData'

type ReaderProps = {
  images: EntryData[]
  maxImageWidth?: number
  borderImage?: boolean
}

export function Reader({ images, maxImageWidth, borderImage }: ReaderProps) {
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
