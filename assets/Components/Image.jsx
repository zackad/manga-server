import { h } from 'preact'

function Image({ src, alt, borderImage }) {
  return (
    <div className={borderImage ? `border-t` : ``}>
      <img className='mx-auto' src={src} alt={alt} />
    </div>
  )
}

export { Image }
