import { h } from 'preact'

import { ArchiveItem } from 'App/Components/List/ArchiveItem'
import { DirectoryItem } from 'App/Components/List/DirectoryItem'
import { FileItem } from 'App/Components/List/FileItem'
import { EntryData } from 'App/types/EntryData'

type ListItemProps = {
  item: EntryData
}

export function ListItem({ item }: ListItemProps) {
  if (item.type === 'directory') return <DirectoryItem {...item} />
  if (item.type === 'archive') return <ArchiveItem {...item} />
  return <FileItem {...item} />
}
