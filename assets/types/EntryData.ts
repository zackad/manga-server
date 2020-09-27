export type EntryData = {
  uri: string
  label: string
  type: 'directory' | 'archive' | 'file'
  cover?: string
}
